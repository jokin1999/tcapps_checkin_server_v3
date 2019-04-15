<!DOCTYPE html>
<html lang="zh-CN" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>Check-in Game</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" charset="utf-8"></script>
  </head>
  <body>

    <!-- 导航条 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand" href="{{ action('UserController@user') }}">
          <img src="{{ asset('favicon.ico') }}" width="30" height="30" class="d-inline-block align-top" alt="">
          User Center
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="{{ action('PublicController@index') }}">首页</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ action('UserController@user') }}">用户中心</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ action('AdminController@index') }}">管理中心</a>
            </li>
          </ul>
          <button class="btn btn-sm btn-outline-light" type="button" onclick="javascript:logout();">退出 / logout</button>
        </div>
      </div>
    </nav>

    @yield('headerExtraContent')

    <div class="container">

      @yield('before_nav')

      <div class="row text-center my-4">
        <div class="col-sm mb-3">
          <button type="button" class="btn btn-primary btn-block" onclick="javascript:location.href='{{ action('UserController@user') }}';">用户中心</button>
        </div>
        <div class="col-sm mb-3">
          <button type="button" class="btn btn-success btn-block" onclick="javascript:location.href='{{ action('UserController@history_checkin') }}';">签到记录</button>
        </div>
        <div class="col-sm mb-3">
          <button type="button" class="btn btn-primary btn-block" onclick="javascript:location.href='{{ action('UserController@shop') }}';">兑换中心</button>
        </div>
        <div class="col-sm mb-3">
          <button type="button" class="btn btn-success btn-block" onclick="javascript:location.href='{{ action('UserController@bill') }}';">积分账单</button>
        </div>
        <div class="col-sm mb-3">
          <button type="button" class="btn btn-primary btn-block" onclick="javascript:location.href='{{ action('UserController@activity') }}';">活动中心</button>
        </div>
      </div>

      @yield('container')

    </div>
    <script type="text/javascript">
      function logout() {
        $.getJSON('/api/logout', function(){
          location.href = '/home';
        });
      }
    </script>
    @yield('script')
  </body>
</html>
