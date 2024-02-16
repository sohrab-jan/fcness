<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Signal;
use App\Models\User;
use App\Models\SignalHistory;
use App\Models\GeneralSetting;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Exception;

class SignalController extends Controller{

    public function signal(){
        $pageTitle = 'Manage Signal';
        $signals = Signal::latest()->paginate(getPaginate());
        $packages = Package::latest()->get();
        return view('admin.signal.index', compact('pageTitle', 'signals', 'packages'));
    }

    public function add(Request $request){

        $request->validate([
            'name'=> 'required|max:250|string',
            'signal'=> 'required|max:65000',
            'setTime'=> 'required|in:0,1',
            'minute' => 'sometimes|integer|gt:0',
            'status' => 'sometimes|in:on',
            'plan' => 'required|exists:packages,id|array',
            'via' => 'required|in:Email,Telegram,SMS|array',
        ]);

        $newSignal = new Signal();
        $newSignal->name = $request->name;
        $newSignal->send_via = json_encode($request->via, true);
        $newSignal->signal = $request->signal;
        $newSignal->status = isset($request->status) ? 1 : 0;
        $newSignal->plan_id = json_encode($request->plan, true);
        $newSignal->minute = $request->setTime == 0 ? 0 : $request->minute;
        $newSignal->send_signal_at = $request->setTime == 0 ? null : Carbon::now()->addMinute($request->minute);
        $newSignal->send = $request->setTime == 0 ? 1 : 0;
        $newSignal->save();

        if($newSignal->status == 1 && $request->setTime == 0){

            $planId = json_decode($newSignal->plan_id, true);
            $users = User::whereDate('validity', '>=', Carbon::now())->whereIn('package_id', $planId)->get();

            foreach($users as $user){
                $signalHistory = new SignalHistory();
                $signalHistory->user_id = $user->id;
                $signalHistory->name = $newSignal->name;
                $signalHistory->signal = $newSignal->signal;
                $signalHistory->save();

                if( in_array('Email', json_decode($newSignal->send_via)) ){
                    sendEmail($user, 'SIGNAL_NOTICATION', [
                        'package'=> $user->package->name,
                        'validity'=> showDateTime($user->validity),
                        'signal_name'=> $newSignal->name,
                        'signal_details'=> $newSignal->signal,
                    ]);
                }

                if( in_array('SMS', json_decode($newSignal->send_via)) ){

                    try{
                        sendSms($user, 'SIGNAL_NOTICATION', [
                            'package'=> $user->package->name,
                            'validity'=> showDateTime($user->validity),
                            'signal_name'=> $newSignal->name,
                            'signal_details'=> $newSignal->signal,
                        ]);
                    }catch(Exception $ex){
                        echo $ex->getMessage();
                    }

                }

                if( in_array('Telegram', json_decode($newSignal->send_via)) ){

                    $general = GeneralSetting::first();

                    try{
                        if($general->api_token != null){

                            $telegramUserUrl = "https://api.telegram.org/bot". $general->api_token ."/getUpdates";

                            $results = Http::get($telegramUserUrl);
                            $jsonUser = json_decode($results);
                            $teleUsers = array();

                            foreach($jsonUser->result as $rs){
                                $username =  @$rs->message->from->username;
                                $chat_id =  @$rs->message->from->id;
                                $teleUsers[$username] = $chat_id;
                            }

                            foreach($teleUsers as $key => $telegramId){

                                $validUser = User::where('telegram_username', $key)
                                                ->whereIn('package_id', json_decode($newSignal->plan_id, true))
                                                ->whereDate('validity', '>=', Carbon::now())
                                                ->first();

                                if($validUser){
                                    $sendUrl = "https://api.telegram.org/bot". $general->api_token ."/sendMessage?chat_id=". $telegramId .'&text='. $newSignal->signal;
                                    Http::get($sendUrl);
                                }

                            }
                        }
                    }catch(Exception $ex){
                        echo $ex->getMessage();
                    }

                }
            }

        }

        $notify[] = ['success', 'Signal Created Successfully'];
        return back()->withNotify($notify);
    }

    public function updatePage($id){
        $pageTitle = 'Update Signal Page';
        $signal = Signal::findOrFail($id);
        $packages = Package::latest()->get();
        $send_via = ['Email', 'SMS', 'Telegram'];
        return view('admin.signal.update', compact('pageTitle', 'signal', 'packages', 'send_via'));
    }

    public function update(Request $request){

        $request->validate([
            'name'=> 'required|max:250|string',
            'signal'=> 'required|max:65000',
            'setTime'=> 'required|in:0,1',
            'minute' => 'sometimes|integer|gt:0',
            'status' => 'sometimes|in:on',
            'id'=> 'required|exists:signals,id',
            'plan' => 'required|exists:packages,id|array',
            'via' => 'required|in:Email,Telegram,SMS|array',
        ]);

        $findSignal = Signal::where('id', $request->id)->where('send', 0)->firstOrFail();
        $findSignal->name = $request->name;
        $findSignal->send_via = json_encode($request->via, true);
        $findSignal->signal = $request->signal;
        $findSignal->minute = $request->setTime == 0 ? 0 : $request->minute;
        $findSignal->status = isset($request->status) ? 1 : 0;
        $findSignal->plan_id = json_encode($request->plan, true);
        $findSignal->send_signal_at = $request->setTime == 0 ? null : Carbon::now()->addMinute($request->minute);
        $findSignal->send = $request->setTime == 0 ? 1 : 0;
        $findSignal->save();

        if($findSignal->status == 1 && $request->setTime == 0){

            $planId = json_decode($findSignal->plan_id, true);
            $users = User::whereDate('validity', '>=', Carbon::now())->whereIn('package_id', $planId)->get();

            foreach($users as $user){
                $signalHistory = new SignalHistory();
                $signalHistory->user_id = $user->id;
                $signalHistory->name = $findSignal->name;
                $signalHistory->signal = $findSignal->signal;
                $signalHistory->save();

                if( in_array('Email', json_decode($findSignal->send_via)) ){
                    sendEmail($user, 'SIGNAL_NOTICATION', [
                        'package'=> $user->package->name,
                        'validity'=> showDateTime($user->validity),
                        'signal_name'=> $findSignal->name,
                        'signal_details'=> $findSignal->signal,
                    ]);
                }

                if( in_array('SMS', json_decode($findSignal->send_via)) ){

                    try{
                        sendSms($user, 'SIGNAL_NOTICATION', [
                            'package'=> $user->package->name,
                            'validity'=> showDateTime($user->validity),
                            'signal_name'=> $findSignal->name,
                            'signal_details'=> $findSignal->signal,
                        ]);
                    }catch(Exception $ex){
                        echo $ex->getMessage();
                    }

                }

                if( in_array('Telegram', json_decode($findSignal->send_via)) ){

                    $general = GeneralSetting::first();

                    try{
                        if($general->api_token != null){

                            $telegramUserUrl = "https://api.telegram.org/bot". $general->api_token ."/getUpdates";

                            $results = Http::get($telegramUserUrl);
                            $jsonUser = json_decode($results);
                            $teleUsers = array();

                            foreach($jsonUser->result as $rs){
                                $username =  @$rs->message->from->username;
                                $chat_id =  @$rs->message->from->id;
                                $teleUsers[$username] = $chat_id;
                            }

                            foreach($teleUsers as $key => $telegramId){

                                $validUser = User::where('telegram_username', $key)
                                                ->whereIn('package_id', json_decode($findSignal->plan_id, true))
                                                ->whereDate('validity', '>=', Carbon::now())
                                                ->first();

                                if($validUser){
                                    $sendUrl = "https://api.telegram.org/bot". $general->api_token ."/sendMessage?chat_id=". $telegramId .'&text='. $findSignal->signal;
                                    Http::get($sendUrl);
                                }

                            }
                        }
                    }catch(Exception $ex){
                        echo $ex->getMessage();
                    }

                }
            }

        }

        $notify[] = ['success', 'Signal Updated Successfully'];
        return back()->withNotify($notify);
    }

    public function delete(Request $request){

        $request->validate([
            'id'=> 'required|exists:signals,id',
        ]);

        $findSignal = Signal::where('id', $request->id)->where('send', 0)->firstOrFail();
        $findSignal->delete();

        $notify[] = ['success', 'Signal Deleted Successfully'];
        return back()->withNotify($notify);
    }



}
