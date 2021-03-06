<?php

namespace App\Http\Controllers\Api;

use DB;
use Captcha;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\BackpackManager as BM;

class CheckIn extends Controller {

    // 清灰
    public function clean() {
      $uid      = request()->cookie('uid');
      $captcha  = request()->post('captcha');
      // 查询登录状态是否正常
      if (is_null($uid) || is_null($captcha)) {
        $json = $this->JSON(3901, 'Bad auth.', null);
        return response($json);
      }
      // 匹配验证码
      if (!Captcha::check($captcha)) {
        $json = $this->JSON(3906, 'Bad captcha.', null);
        return response($json);
      }
      // 查询用户信息
      $user = DB::table('v3_user_accounts')
                ->where('uid', $uid)
                ->sharedLock()
                ->first();
      if (!$user) {
        $json = $this->JSON(3902, 'Bad user id.', null);
        return response($json);
      }
      // 判断用户状态
      $banedStatus = [-2, -1, 0];
      if (in_array($user->status, $banedStatus)) {
        $json = $this->JSON(3903, 'Incorrect user status.', null);
        return response($json);
      }
      // 查询上次擦灰时间
      $db = DB::table('v3_clean_list')
          ->where('uid', $user->uid)
          ->first();
      if ($db && $db->check_time >= date('Y-m-d 00:00:00')) {
        $json = $this->JSON(3904, 'Incorrect clean time.', null);
        return response($json);
      }
      // 是否需要新建记录
      $need_insert = !$db;
      $check_time = date('Y-m-d H:i:s');
      $data = array(
        'uid'         => $user->uid,
        'check_time'  => $check_time
      );
      if ($need_insert) {
        $db = DB::table('v3_clean_list')->sharedLock()->insert($data);
      }else{
        $db = DB::table('v3_clean_list')->where('uid', $user->uid)->sharedLock()->update($data);
      }
      if (!$db) {
        $json = $this->JSON(3905, 'Unknown error.', null);
        return response($json);
      }
      // 注册积分
      $db = DB::table('v3_user_point')->where('uid', $user->uid)->sharedLock()->first();
      // 是否需要新建记录
      $need_insert = !$db;
      // 固定擦灰积分
      $worth = rand(1, 50);
      // 计算总积分
      if ($db) {
        $point = $db->point + $worth;
      }else{
        $point = $worth;
      }
      $data = array(
        'uid'     => $user->uid,
        'point'   => $point
      );
      // 写入积分
      if ($need_insert) {
        $db = DB::table('v3_user_point')->sharedLock()->sharedLock()->insert($data);
      }else{
        $db = DB::table('v3_user_point')->where('uid', $user->uid)->sharedLock()->update($data);
      }
      // 只有1-2个积分的时候，奖励1个可莫尔碎片
      if ($worth >= 1 && $worth <= 10) {
        $comber = rand(1, 4); // 四种碎片随机
        // 查询用户背包
        $db = BM::uid($uid)->add($comber, 1, BM::GENERAL);
        if (!$db) {
          $json = $this->JSON(3908, 'Unknown error.', null);
          return response($json);
        }
        // 生成提示信息
        $awards = array(
          'point'   => $worth,
          'comber'  => $comber
        );
      }else{
        $awards = array(
          'point'   => $worth,
          'comber'  => -1
        );
      }
      if (!$db) {
        $json = $this->JSON(3907, 'Unknown error.', null);
        return response($json);
      }else{
        $json =  $this->JSON(0, null, ['msg'  => 'Success!', 'data' => $awards]);
        return response($json);
      }
    }
}
