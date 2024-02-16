<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SignalHistory;
use App\Models\Signal;
use App\Models\User;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class CronController extends Controller{

    public function sendSignal(){

        $signals = Signal::where('status', '1')->where('send_signal_at', '!=', null)->where('send', 0)->where('send_signal_at', '<', Carbon::now())->get();

        foreach($signals as $signal){

            $planId = json_decode($signal->plan_id, true);

            $users = User::whereDate('validity', '>=', Carbon::now())->whereIn('package_id', $planId)->get();

            foreach($users as $user){
                $this->signal($user, $signal);
            }

            $signal->send = 1;
            $signal->save();

        }


    }

    protected function signal($user, $signal){

        $signalHistory = new SignalHistory();
        $signalHistory->user_id = $user->id;
        $signalHistory->name = $signal->name;
        $signalHistory->signal = $signal->signal;
        $signalHistory->save();

        if( in_array('Email', json_decode($signal->send_via)) ){
            sendEmail($user, 'SIGNAL_NOTICATION', [
                'package'=> $user->package->name,
                'validity'=> showDateTime($user->validity),
                'signal_name'=> $signal->name,
                'signal_details'=> $signal->signal,
            ]);
        }

        if( in_array('SMS', json_decode($signal->send_via)) ){

            try{
                sendSms($user, 'SIGNAL_NOTICATION', [
                    'package'=> $user->package->name,
                    'validity'=> showDateTime($user->validity),
                    'signal_name'=> $signal->name,
                    'signal_details'=> $signal->signal,
                ]);
            }catch(Exception $ex){
                echo $ex->getMessage();
            }

        }

        if( in_array('Telegram', json_decode($signal->send_via)) ){

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
                                         ->whereIn('package_id', json_decode($signal->plan_id, true))
                                         ->whereDate('validity', '>=', Carbon::now())
                                         ->first();

                        if($validUser){
                            $sendUrl = "https://api.telegram.org/bot". $general->api_token ."/sendMessage?chat_id=". $telegramId .'&text='. $signal->signal;
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


