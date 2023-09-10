<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page not found!!</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap');
        * {padding: 0;margin: 0;box-sizing: border-box;}
        .error-wrapper {display: flex;justify-content: center;align-items: center;height: 100vh;}
        .content {text-align: center;}
        img.error-img {border: 1px solid #ddd;}
        h1.error-hedding {margin-top: 30px;margin-bottom: 10px;font-family: 'Abril Fatface', cursive;}
        .content-text {width: 400px;}
        a.go-home-btn {padding: 10px 24px;background: #262f5f;border-radius: 20px;color: #fff;text-decoration: none;font-weight: 600;transition: .3s;}
        p.error-des {margin-bottom: 29px;font-family: none;color: #484848;}
        a.go-home-btn:hover {background: #000000;}
    </style>
</head>

<body>
    <div class="error-wrapper">
        <div class="content">
            <img src="{{asset('backend/asset/img/errors/')}}/404-pos.png" alt="" class="error-img">

            <div class="content-text">
                <h1 class="error-hedding">@lang('Page Not Found')</h1>
                <br>
                <a href="{{ route('dashboard.dashboard') }}" class="go-home-btn">@lang('Go Home')</a>
            </div>
        </div>
    </div>
</body>
</html>
