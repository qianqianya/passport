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
        $where=[
            'u_email'=>$email
        ];
        $u_pwd=UserModel::where($where)->first();
        if($u_pwd){
            if($u_pwd['u_pwd']==$pwd){
                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                //记录web记录token
                $redis_key_web_token='str:u:token:web'.$u_pwd->u_id;
                Redis::del($redis_key_web_token);
                Redis::hset($redis_key_web_token,'and',$token);
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

