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
                $redis_key_web_token='str:u:token:app'.$u_pwd->u_id;
                Redis::hdel($redis_key_web_token);
                Redis::hset($redis_key_web_token,111,$token);
                $data=[
                    'token'=>$token,
                    'u_id'=>$u_pwd->u_id
                ];
                $res=json_encode($data,true);
                if($res){
                    echo '登陆成功';
                }
            }else{
                $data=[
                    'error'=>'密码错误'
                ];
                echo json_encode($data);
            }
        }else{
            $data=[
                'error'=>'该用户不存在'
            ];
            echo json_encode($data);
        }
    }

    //用户中心验证
    public function token()
    {
        $uid=$_POST['u_id'];
        $oldtoken=$_POST['token'];
        $newtoken=Redis::get("token:one:$uid");
        if($oldtoken==$newtoken){
            return 1;
        }else{
            return 0;
        }
    }

    public function quit()
    {
        $uid=$_POST['u_id'];
        $newtoken=Redis::hdel("token:one:$uid");
        return $newtoken;
    }

}

