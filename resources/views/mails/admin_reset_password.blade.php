<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        h1,h2,h3,p{
            text-align: center
        }
        a{
            background-color: rgb(0, 176, 140);
            padding: 10px 15px;
            text-decoration: none;
            color: #fff;
        }
        div{
            text-align: center
        }
    </style>
</head>
<body>
    <h2>welcome {{$data['data']->name}}</h2>
    <h1>reset your password</h1>

    <div>
        <p>click here to reset your password</p>
        <a href="{{url("admin/reset_password/" . $data["token"])}}">reset now</a>
    </div>
</body>
</html>