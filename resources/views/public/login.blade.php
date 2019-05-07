@extends('public.master')
@section('headerExtraContent')
  <!-- 幕布 -->
  <div class="jumbotron">
      <div class="container">
        <h1 class="display-4">Check-in Game 登录</h1>
        <p class="lead">签到排行榜实时更新，签到每隔5分钟即可进行一次，只需简单注册账户即可开始游戏！</p>
        <p class="lead mb-0">
          <a class="btn btn-primary" href="{{ action('PublicController@index') }}">首页</a>
          <a class="btn btn-success" href="{{ action('PublicController@register') }}">注册账户</a>
        </p>
      </div>
    </div>
  <div class="container">
@endsection
@section('container')
  <!-- 公告-4 -->
  @foreach($_notices as $notice)
  <div class="alert alert-{{ $notice['color'] }}" role="alert">
    @if (!empty($notice['title']))
    <h4 class="alert-heading">{{ $notice['title'] }}</h4>
    @endif
    {{ $notice['content'] }}
  </div>
  @endforeach

  <!-- 登录 -->
  <h2>登录 / Login</h2>
  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">用户名</span>
    </div>
    <input type="text" class="form-control" placeholder="Username" aria-label="Username" id="username">
  </div>

  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">密码</span>
    </div>
    <input type="password" class="form-control" placeholder="Password" aria-label="Password" id="password">
  </div>

  <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">验证码</span>
    </div>
    <input type="text" class="form-control" placeholder="Captcha" id="captcha" maxlength="6">
    <div class="input-group-append">
      <img src="{{ captcha_src() }}" alt="captcha" onclick="this.src='{{ captcha_src() }}' + Math.random();" id="captcha_img">
    </div>
  </div>

  <p class="clearfix">
    <button class="btn btn-success float-right" id="btn" name="button" onclick="javascript:login();">登录</button>
  </p>

  <div class="alert alert-warning" role="alert">
    <h4 class="alert-heading">安全提示</h4>
    <p class="mb-0">管理员不会向您索要您帐号的密码，请勿将密码透露给任何人！</p>
  </div>

@endsection
@section('script')
<script src="{{ asset('js/base64.js') }}" charset="utf-8"></script>
<script type="text/javascript">
  $(function(){
    $(document).keyup(function(){
    	if(event.keyCode==13){
    		login();
    	}
    });
  });
  function login() {
    let username = $('#username').val();
    let password = $('#password').val();
    let captcha  = $('#captcha').val();
    let b64password = new Base64().encode(password);
    if (username.length < 5 || username.length > 16) {
      m_alert('用户名或密码错误', 'warning');
      return false;
    }
    if (password.length < 8 || password.length > 16) {
      m_alert('用户名或密码错误', 'warning');
      return false;
    }
    if (captcha.length < 4 || captcha.length > 6) {
      $('#captcha').val('');
      m_alert('验证码错误', 'warning');
      return false;
    }
    m_loading();
    $.ajax({
      url: '/api/login',
      type: 'post',
      data: {
        'username': username,
        'password': b64password,
        'captcha' : captcha
      },
      dataType: 'json',
      timeout: 10000,
      complete: function(XMLHttpRequest, status){
        m_loading(false);
        if (status == 'timeout') {
          alert('连接超时！');
        }
      },
      success: function(data){
        $('#captcha_img').click();
        $('#captcha').val('');
        if (data.errno === 0) {
          location.href = '/user';
        }else if(data.errno === 2307){
          m_alert('服务器开小差辣~', 'danger');
        }else if(data.errno === 2305){
          m_alert('验证码错误', 'danger');
        }else{
          m_alert('用户名或密码错误');
        }
      }
    });
  }
</script>
@endsection
