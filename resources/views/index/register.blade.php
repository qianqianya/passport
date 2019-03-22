<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>注册</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{URL::asset('/bootstrap/css/bootstrap.min.css')}}">
</head>
<body>
    <h1 align="center">注册</h1>
    <table border="1" align="center">
        <tr>
            <td>邮箱：</td>
            <td><input type="text" id="email" name="email"></td>
        </tr>
        <tr>
            <td>密码：</td>
            <td><input type="password" id="pwd1" name="pwd1"></td>
        </tr>
        <tr>
            <td>确认密码：</td>
            <td><input type="password" id="pwd2" name="pwd2"></td>
        </tr>
        <tr>
            <td><a href="http://vm.passport.com/login">登录页面</a></td>
            <td><input  class="btn btn-success" onclick="register()" value="注册"></td>
        </tr>
    </table>
</body>
</html>
<script src="{{URL::asset('/js/jquery-1.12.4.min.js')}}"></script>
<script src="{{URL::asset('/bootstrap/js/bootstrap.min.js')}}"></script>
<script>
    function register(){
        var email = document.getElementById('email').value;
        var pwd1 = document.getElementById('pwd1').value;
        var pwd2 = document.getElementById('pwd2').value;

        if(email==""){
            alert('邮箱不能为空');
            return false;
        }else if(pwd1==""){
            alert('密码不能为空');
            return false;
        }else if(pwd2==""){
            alert('确认密码不能为空');
            return false;
        }else if(pwd1!=pwd2){
            alert('确认密码和密码不一致');
            return false;
        }


        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/register',
            data: {email:email,pwd1:pwd1,pwd2:pwd2},
            async: true, // 异步 || 同步
            dataType: 'json',
            type: 'post',
            timeout: 10000,
            success: function (data) {
                // 请求成功
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