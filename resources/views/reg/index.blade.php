<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>个人信息</title>
</head>
<body>
    <table>
        <tr>
            <td>账号</td>
        </tr>
        <tr>
            <td>{{  cache('u_id_'.session('u_id')) -> u_email }}</td>
        </tr>
    </table>
</body>
</html>