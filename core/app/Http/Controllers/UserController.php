<?php

namespace App\Http\Controllers;

use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\Deposit;
use App\Models\Package;
use App\Models\SignalHistory;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\SupportTicket;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {
        $pageTitle = 'Dashboard';
        $user = Auth::user();
        $totalDeposit = Deposit::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $totalTrx = Transaction::where('user_id', $user->id)->count();
        $totalSignal = SignalHistory::where('user_id', $user->id)->count();
        $latestTrxs = Transaction::where('user_id', $user->id)->latest()->limit(10)->get();
        $totalTicket = SupportTicket::where('user_id', $user->id)->count();
        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'user', 'totalDeposit', 'totalTrx', 'totalSignal', 'latestTrxs', 'totalTicket'));
    }

    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = Auth::user();
        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'telegram' => 'required|max:255',
            'address' => 'sometimes|required|max:80',
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])]
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required'
        ]);

        $user = Auth::user();

        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;
        $in['telegram_username'] = $request->telegram;

        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];


        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        $user->fill($in)->save();
        $notify[] = ['success', 'Profile updated successfully.'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change password';
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$password_validation]
        ]);


        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /*
     * Deposit History
     */
    public function depositHistory()
    {
        $pageTitle = 'Deposit History';
        $emptyMessage = 'No history found.';
        $logs = auth()->user()->deposits()->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.deposit_history', compact('pageTitle', 'emptyMessage', 'logs'));
    }

    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->sitename, $secret);
        $pageTitle = 'Two Factor';
        return view($this->activeTemplate.'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request){
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function trxHistory(){
        $pageTitle = 'Transaction History';
        $logs  = Transaction::where('user_id', Auth::user()->id)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.trx_history', compact('pageTitle', 'logs'));
    }

    public function signalHistory(){
        $pageTitle = 'Signal History';
        $logs  = SignalHistory::where('user_id', Auth::user()->id)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.signal_history', compact('pageTitle', 'logs'));
    }

    public function buyPackage(Request $request){

        $request->validate([
            'id' => [
                'required',
                Rule::exists('packages')->where(function ($query) use($request) {
                    return $query->where('id', $request->id)
                    ->where('status', 1);
                })
            ]
        ]);

        $findPackage = Package::find($request->id);
        $user = Auth::user();

        if($findPackage->price > $user->balance){
            $notify[] = ['error', 'Sorry, Insufficient balance'];
            return back()->withNotify($notify);
        }

        $user->package_id = $findPackage->id;
        $user->validity = Carbon::now()->addDay($findPackage->validity);
        $user->balance -= $findPackage->price;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $findPackage->price;
        $transaction->post_balance = $user->balance;
        $transaction->charge = 0;
        $transaction->trx_type = '-';
        $transaction->details = 'Purchased ' .$findPackage->name;
        $transaction->trx =  getTrx();
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = $user->username.' has purchased '.$findPackage->name;
        $adminNotification->click_url = urlPath('admin.users.transactions', $user->id);
        $adminNotification->save();

        $general = GeneralSetting::first();

        notify($user, 'PURCHASE_COMPLETE', [
            'trx' => $transaction->trx,
            'package' => $findPackage->name,
            'amount' => showAmount($findPackage->price, 2),
            'currency' => $general->cur_text,
            'post_balance' => showAmount($user->balance, 2),
            'validity' => $findPackage->validity.' Days',
            'expired_validity' => $user->validity,
            'buy_at' => showDateTime($transaction->created_at),
        ]);

        $notify[] = ['success', 'You have purchased '.$findPackage->name.' successfully'];
        return redirect()->route('user.trx.history')->withNotify($notify);

    }

    public function renewPackage(Request $request){

        $request->validate([
            'id' => [
                'required',
                Rule::exists('packages')->where(function ($query) use($request) {
                    return $query->where('id', $request->id)
                    ->where('status', 1);
                })
            ]
        ]);

        $findPackage = Package::find($request->id);
        $user = Auth::user();

        if($user->package_id == 0){
            $notify[] = ['error', 'Sorry, You have no package to renew'];
            return back()->withNotify($notify);
        }

        if($findPackage->price > $user->balance){
            $notify[] = ['error', 'Sorry, Insufficient balance'];
            return back()->withNotify($notify);
        }

        $user->validity = Carbon::parse($user->validity)->addDay($findPackage->validity);
        $user->balance -= $findPackage->price;
        $user->save();

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $findPackage->price;
        $transaction->post_balance = $user->balance;
        $transaction->charge = 0;
        $transaction->trx_type = '-';
        $transaction->details = 'Renewed ' .$findPackage->name;
        $transaction->trx =  getTrx();
        $transaction->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = $user->username.' has renewed '.$findPackage->name;
        $adminNotification->click_url = urlPath('admin.users.transactions', $user->id);
        $adminNotification->save();

        $general = GeneralSetting::first();

        notify($user, 'RENEW_COMPLETE', [
            'trx' => $transaction->trx,
            'package' => $findPackage->name,
            'amount' => showAmount($findPackage->price, 2),
            'currency' => $general->cur_text,
            'post_balance' => showAmount($user->balance, 2),
            'validity' => $findPackage->validity.' Days',
            'expired_validity' => $user->validity,
            'renew_at' => showDateTime($transaction->created_at),
        ]);

        $notify[] = ['success', 'You have renewed '.$findPackage->name.' successfully'];
        return redirect()->route('user.trx.history')->withNotify($notify);

    }

}




