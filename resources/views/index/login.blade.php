<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <h1 align="center">登录</h1>
    <table border="1" align="center">
        <tr>
            <td>邮箱：</td>
            <td><input type="text" id="email" name="email"></td>
        </tr>
        <tr>
            <td>密码：</td>
            <td><input type="password" id="pwd" name="pwd"></td>
        </tr>
        <tr>
            <td><a href="http://vm.passport.com/register">注册页面</a></td>
            <td><input onclick="login()"  class="btn btn-success" value="登录"></td>
        </tr>
    </table>
</body>
</html>
<script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
<script src="{{URL::asset('/bootstrap/js/bootstrap.min.js')}}"></script>
<script>
    function login(){
        var email = $("#email").val();
        var pwd = $("#pwd").val();

        if(email==""){
            alert('邮箱不能为空');
            return false;
        }else if(pwd==""){
            alert('密码不能为空');
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url     :   '/login',
            data: {email:email,pwd:pwd},
            async: true, // 异步 || 同步
            dataType: 'json',
            type: 'post',
            timeout: 10000,
            success :   function(data){
                if(data.errno == 0){
                    alert(data.msg);
                    location.href = "{{$redirect}}";
                }else{
                    var msg = data.errno + ":" + data.msg;
                    alert(msg);
                }
            }
        });
    }

</script>