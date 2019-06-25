<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>登录页面</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
</head>
<body>
    <form action="/reg/doLogin" method="post">
    @if (session('msg'))
        <div class="alert alert-success">
        {{ session('msg') }}
        </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
    @endif
    @csrf
        <table>
            <tr>
                <td>账号</td>
                <td><input type="text" name="u_email"><span></span></td>
            </tr>
            <tr>
                <td>密码</td>
                <td><input type="password" name="u_pwd"><span></span></td>
            </tr>
            <tr>
                <td><button>登录</button></td>
                <td><input type="reset" value="重置"></td>
            </tr>
        </table>
    </form>
</body>
</html>
