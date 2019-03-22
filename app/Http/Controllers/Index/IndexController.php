<?php

namespace App\Http\Controllers\Index;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function login(Request $request)
    {

        $email=$request->input('email');
        $pwd=$request->input('pwd');

        $where=[
            'email'=>$email
        ];
        $userInfo=UserModel::where($where)->first();
        if(empty($userInfo)){
            $response = [
                'errno' =>  40001,
                'msg'   =>  '用户名不存在'
            ];
            return $response;
        }
        $pas = $userInfo->pwd;
        if(password_verify($pwd,$pas)){
            $id = $userInfo->id;
            $key = 'token:' . $id;
            $token = substr(md5(time() + $id + rand(1000,9999)),10,20);
            Redis::set($key,$token);
            Redis::setTimeout($key,60*60*24*7);
            setcookie('pass_id',$id,time()+86400,'/','vm.passport2.com',false,true);
            setcookie('pass_token',$token,time()+86400,'/','vm.passport2.com',false,true);
            $request->session()->put('pass_port_token',$token);
            $request->session()->put('pass_port_id',$id);
            $response = [
                'errno' =>  0,
                'msg'   =>  '登陆成功',
            ];
        }else{
            $response = [
                'errno' =>  40002,
                'msg'   =>  '登录失败'
            ];
        }
        return $response;

    }

    public function register(Request $request)
    {
        $email=$request->input('email');
        $pwd1=$request->input('pwd1');
        $pwd2=$request->input('pwd2');

        $re=UserModel::where(['email'=>$email])->first();
        if($re){
            $response = [
                'errno' =>  40004,
                'msg'   =>  '邮箱已存在'
            ];
            return $response;
        }
        if($pwd2!==$pwd1){
            $response = [
                'errno' =>  40005,
                'msg'   =>  '密码与确认密码不一致'
            ];
            return $response;
        }
        $pas=password_hash($pwd2,PASSWORD_BCRYPT);
        $data=[
            'email'=>$email,
            'pwd'=>$pas,
            'atime'=>time(),
        ];
        $id=UserModel::insertGetId($data);
        if($id){
            $key = 'token:' . $id;
            $token = substr(md5(time() + $id + rand(1000,9999)),10,20);
            Redis::set($key,$token);
            Redis::setTimeout($key,60*60*24*7);
            setcookie('pass_uid',$id,time()+86400,'/','vm.passport.com',false,true);
            setcookie('pass_token',$token,time()+86400,'/','vm.passport.com',false,true);
            $request->session()->put('pass_port_token',$token);
            $request->session()->put('pass_port_uid',$id);
            $response = [
                'errno' =>  0,
                'msg'   =>  '注册成功'
            ];
        }else{
            $response = [
                'errno' =>  40006,
                'msg'   =>  '注册失败'
            ];
        }
        return $response;
    }

    public function loginView(Request $request)
    {
        $redirect = urldecode($request->input('redirect'));
        if(empty($redirect)){
            $redirect = env('SHOP_URL');
        }
        $info = [
            'redirect'  =>  $redirect,
        ];
        return view('index.login',$info);
    }

    public function registerView(Request $request)
    {
        $redirect = urldecode($request->input('redirect'));
        if(empty($redirect)){
            $redirect = env('SHOP_URL');
        }
        $info = [
            'redirect'  =>  $redirect,
        ];
        return view('index.register',$info);
    }

    public function apiLogin(Request $request)
    {
        $email = $request->input('email');
        $pwd = $request->input('pwd');
        $where=[
            'email'=>$email
        ];
        $userInfo=UserModel::where($where)->first();
        if(empty($userInfo)){
            $response = [
                'errno' =>  40001,
                'msg'   =>  '用户名不存在'
            ];
            return $response;
        }
        $pas = $userInfo->password;
        if(password_verify($pwd,$pas)){
            $uid = $userInfo->uid;
            $key = 'api:token:' . $uid;
            $token = substr(md5(time() + $uid + rand(1000,9999)),10,20);
            Redis::set($key,$token);
            Redis::setTimeout($key,60*60*24*7);
            $response = [
                'errno' =>  0,
                'msg'   =>  '登陆成功',
                'token' =>  $token
            ];
        }else{
            $response = [
                'errno' =>  40002,
                'msg'   =>  '登录失败'
            ];
        }
        return $response;
    }

    public function apiRegister(Request $request)
    {
        $name=$request->input('name');
        $tel=$request->input('tel');
        $email=$request->input('email');
        $pwd=$request->input('pwd');

        $re=UserModel::where(['email'=>$email])->first();
        if($re){
            $response = [
                'errno' =>  40004,
                'msg'   =>  '邮箱已存在'
            ];
            return $response;
        }
        $pas=password_hash($pwd,PASSWORD_BCRYPT);
        $data=[
            'name'=>$name,
            'email'=>$email,
            'tel'=>$tel,
            'pwd'=>$pas,
            'atime'=>time(),
        ];
        $id=UserModel::insertGetId($data);
        if($id){
            $response = [
                'errno' =>  0,
                'msg'   =>  '注册成功'
            ];
        }else{
            $response = [
                'errno' =>  40006,
                'msg'   =>  '注册失败'
            ];
        }
        return $response;
    }
}
