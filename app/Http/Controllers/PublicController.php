<?php

namespace App\Http\Controllers;

use Cookie;
use Captcha;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PublicController extends Controller {

    public function index() {
      return view('public.index');
    }

    public function register() {
      if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['comfirm'])
        && !empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['comfirm'])
        && isset($_POST['captcha']) && !empty($_POST['captcha']) ){
        // 查询系统是否允许注册
        $reg = DB::table('system')->where('skey', 'register_available')->first();
        if (!$reg || $reg->svalue === 'false') {
          return view('public.register',[
            'reg_status' => false,
            'reg_error' => '非常抱歉！注册通道被临时关闭，请留意首页公告。',
          ]);
        }
        // 进入注册流程
        $username = $_POST['username'];
        $password = $_POST['password'];
        $comfirm = $_POST['comfirm'];
        $captcha = $_POST['captcha'];
        // 判断合法性
        if (mb_strlen($username) > 16 || mb_strlen($username) < 5 || mb_strlen($password) > 16 || mb_strlen($password) < 8) {
          return view('public.register',[
            'reg_status' => false,
            'reg_error' => '注册失败！用户名长度不得超过16位，不得低于5位；密码长度不得超过16位，不得低于8位。',
          ]);
        }
        // 检查一致性
        if ($password !== $comfirm) {
          return view('public.register',[
            'reg_status' => false,
            'reg_error' => '注册失败！两次密码不一致。',
          ]);
        }
        $pattern = "/^[a-zA-Z0-9_]+$/";
        $preg = preg_match($pattern, $username);
        if (!$preg) {
          return view('public.register',[
            'reg_status' => false,
            'reg_error' => '注册失败！用户名中不能包含特殊字符。',
          ]);
        }
        // 检查验证码
        if (!Captcha::check($captcha)) {
          return view('public.register',[
            'reg_status' => false,
            'reg_error' => '验证码不正确。',
          ]);
        }
        // 检查用户是否存在
        $db = DB::table('user_accounts')->where('username', $username)->first();
        if ($db) {
          // 用户已经存在
          return view('public.register',[
            'reg_status' => false,
            'reg_error' => '注册失败！该用户名可能已经存在。',
          ]);
        }
        $password = $this->generate_password($password);
        // 写入用户表
        $data = array(
          'username'    => $username,
          'password'    => $password,
          'status'      => 1          // 有效用户
        );
        $uid = DB::table('user_accounts')->insertGetId($data);
        if (!$uid) {
          return view('public.register',[
            'reg_status' => false,
            'reg_error' => '注册失败！未知原因。',
          ]);
        }else{
          $auth = $this->generate_auth($password, $uid, 1);
          Cookie::queue('uid', $uid);
          Cookie::queue('auth', $auth);
          return redirect('user');
        }
      }else{
        return view('public.register');
      }
    }

    // 登录
    public function login() {
      return view('public.login');
    }

    // 老用户数据迁移
    public function login_old() {
      return view('public.login_old');
    }

    // 告警页面
    public function alert($error, $content) {
      return view('public.alert', ['error' => $error, 'content' => $content]);
    }
}
