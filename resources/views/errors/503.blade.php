<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('Under Development')</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap');
        body {font-family: 'Roboto', sans-serif;}
        .wraper {height: 95vh;display: flex;justify-content: center;align-items: center;}
        .content {text-align: center;}
        .content h1 {font-size: 80px; margin: 12px;color: #f00;}
        p.des {margin: 0;font-size: 25px;}
    </style>
</head>

<body>
    <div class="wraper">
        <div class="content">
            {{-- <h1>@lang('Software Is Under Maintenance')</h1> --}}
            <p class="des">@lang('Our website is under construction').</p>
        </div>
    </div>
</body>
</html>