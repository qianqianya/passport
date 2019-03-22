<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function passport(Request $request)
    {
        $email=$request->input('u_email');
        $pwd=$request->input('u_pwd');
        $type=$request->input('type');
        $where=[
            'u_email'=>$email
        ];
        $u_pwd=UserModel::where($where)->first();
        if($u_pwd){
            if($u_pwd['u_pwd']==$pwd){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                //记录web记录token
                if($type==1){
                    $redis_key_web_token='str:u:token:web'.$u_pwd->u_id;
                    Redis::delete();
                    Redis::set($redis_key_web_token,$token);
                    Redis::expire($redis_key_web_token,86400);
                }elseif($type==2){
                    $redis_key_web_token='str:u:token:app'.$u_pwd->u_id;
                    Redis::delete();
                    Redis::set($redis_key_web_token,$token);
                    Redis::expire($redis_key_web_token,86400);
                }

                $data=[
                'token'=>$token
                ];
                echo json_encode($data);
            }else{
                $data=[
                'error'=>'2222'
                ];
                echo json_encode($data);
            }
        }else{
            $data=[
            'error'=>'11'
            ];
            echo json_encode($data);
        }
    }
}
