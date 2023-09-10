<!DOCTYPE html>
<html>

<head>
  <title>Login Page | MetaShops</title>
  <!-- Bootstrap 5 Alpha CSS CDN -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<body>
    <!-- Animated Background -->
    <div class="parallelogram" id="one"></div>
    <div class="parallelogram" id="two"></div>
    <div class="parallelogram" id="three"></div>
    <div class="parallelogram" id="four"></div>
    <div class="parallelogram" id="five"></div>
    <div class="parallelogram" id="six"></div>
<!-- Animated Background -->
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="card login-content shadow-lg border-0">
          <div class="card-body">
            <div class="text-center">
              <img class="logo" src="https://cdn3.iconfinder.com/data/icons/galaxy-open-line-gradient-i/200/account-256.png">
            </div>
            <h3 class="text-logo">@lang('User Login')</h3>
            <br>
            <form class="text-center" action="{{ route('login') }}" method="POST">
                @csrf
              <input class="form-control border-0" type="text" name="username"
              value="{{ old('username') }}" placeholder="@lang('Username')" required >
              <br>
              <input class="form-control border-0" name="password" type="Password"
               placeholder="@lang('Password')" required>
               @if (Session::has('errorMsg'))
               <div class="bg-danger p-3 mt-4">
                   <p class="text-white">
                       {{ session('errorMsg') }}
                   </p>
               </div>
           @endif
              <br>
              <button class="btn btn-primary btn-sm border-0" type="submit" name="submit">@lang('Login')</button>
              <p class="forgot">
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                @endif
            </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="copyright">
  <h4><a href="https://www.metashops.com.sa" target="_blank" class="comp-name">MetaShops  </a> جميع الحقوق محفوظة   </h4>
  </div>

</body>

</html>


<style>
.copyright{
    background-color:#f38943;
    width:300px;
    display: flex;
    justify-content: center;
    position: absolute;
    bottom: 0;
    right: 0px;
    border:1px solid #333;
    border-top-left-radius: 15px;
    border-bottom-right-radius: 15px;
}
.copyright h4{
    color: #FFF;
    font-size: 14px;
    font-weight: bold;
}
.comp-name{
    color:#333;
    text-decoration:none;
}
    @import url("https://fonts.googleapis.com/css2?family=Lato&display=swap");

body {
  background-color: #2a2c3b;
  /* background-color: #222; */
  font-family: "Lato", sans-serif;
  text-align:center;
}

.login-content {
  max-width: 450px;
  width: 100%;
  height: 550px;
  z-index: 1;
  position: absolute;
  top: 50%;
  left: 50%;
  margin-left: -200px;
  margin-top: -286px;
  border-radius: 8px;
  background: #2f3242;
  transition:all .3s ease-in-out;
}

.logo {
  width: 128px;
  height: 128px;
  margin: 5px;
  border-radius: 50%;
  text-align: center;
  line-height: 128px;
}

.text-logo {
  text-align: center;
  font-weight: bold;
  font-size: 32px;
  color: white;
}

.form-control {
  width: 18rem;
  height: 2rem;
  margin: 10px 0 10px 0;
  position: relative;
  border-radius: 5px;
  padding:5px;
  border:none;
  caret-color:#f38943;
  background-color: #FFF;
}
.form-control:focus{
    outline:none;
    background-color: #EEE;
}

.btn {
  font-size: 22px;
  background-color: #d9804f;
  border:1px solid #d9804f;
  width: 18rem;
  height: 3rem;
  margin-top:15px;
  border-radius: 26px;
  cursor:pointer;
  color:#FFF;
  transition:all .3s ease-in-out;
}

.btn:hover {
    filter: brightness(.9);
}

.forgot {
  position: relative;
  right: 0%;
  top: 14px;
}

.forgot a {
  text-decoration: none;
  font-size: 14px;
  color: rgb(158, 163, 240);
  transition:all .3s ease-in-out;
}
.forgot a:hover{
    text-decoration:underline;
}

/*support google chrome*/
.form-control::-webkit-input-placeholder {
  color:#000 ;
  padding:5px;
  font-size:14px;
  font-weight: 500;
}

/* Animated Background */
.parallelogram {
  -webkit-transform: skew(-40deg);
  -moz-transform: skew(-40deg);
  -o-transform: skew(-40deg);
  transform: skew(-40deg);
  position: absolute;
  z-index: -9;
}

#one {
  width: 20px;
  height: 300px;
  animation: moveBar 15s linear infinite;
  -webkit-box-shadow: 100px 509px #d9804f, 20px 300px #d9804f, -120px 150px #d9804f;
  -moz-box-shadow: 100px 509px #f38943, 20px 300px #f38943, -120px 150px #f38943;
  box-shadow: 100px 509px #f8bd7f, 20px 300px #f8bd7f, -120px 150px #f8bd7f;
}

#two {
  width: 10px;
  height: 300px;
  animation: moveBar 30s linear infinite;
  -webkit-box-shadow: 250px 450px #d9804f, -50px 200px #d9804f;
  -moz-box-shadow: 250px 450px #d9804f, -50px 200px #d9804f;
  box-shadow: 250px 450px #d9804f, -50px 200px #d9804f;
}

#three {
  width: 10px;
  height: 500px;
  animation: moveBar 20s linear infinite;
  -webkit-box-shadow: 70px 500px #f38943, -100px 200px #f38943;
  -moz-box-shadow: 70px 500px #f38943, -100px 200px #f38943;
  box-shadow: 70px 500px #f38943, -100px 200px #f38943;
}

@keyframes moveBar {
  100% {
    transform: skew(-40deg) translateY(-1000px);
  }
}
/* Animated Background */
</style>
@push('js')

@endpush
