<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller {
    // 管理中心
    public function index() {
      return view('admin.home');
    }

    // 管理中心
    public function update() {
      return view('admin.update');
    }

    // // 增加补偿
    // public function compensate() {
    //   return view('admin.compensate');
    // }
    //
    // // 活动一览
    // public function activity() {
    //   $activities = DB::table('activity')->orderBy('aid', 'desc')->paginate(25);
    //   $data = [
    //     'charts'  => $activities,
    //   ];
    //   return view('admin.activity', $data);
    // }
    //
    // // 活动管理
    // public function activity_manage() {
    //   return view('admin.activity_manage');
    // }
    //
    // // 商店一览
    // public function goods() {
    //   $notices = DB::table('shop')->paginate();
    //   $data = [
    //     'charts'  => $notices,
    //   ];
    //   return view('admin.goods', $data);
    // }
    //
    // // 商店管理
    // public function goods_manage() {
    //   return view('admin.goods_manage');
    // }
    //
    // // 优化页
    // public function optimize() {
    //   return view('admin.optimize');
    // }
    //
    // // 公告一览页
    // public function notices() {
    //   $notices = DB::table('notices')->orderBy('nid', 'desc')->paginate();
    //   $data = [
    //     'charts'  => $notices,
    //   ];
    //   return view('admin.notices', $data);
    // }
    //
    // // 公告管理页
    // public function notices_manage() {
    //   return view('admin.notices_manage');
    // }
    //
    // // 用户一览
    // public function users_list() {
    //   $users = DB::table('user_accounts')->paginate(25);
    //   $data = [
    //     'charts'  => $users,
    //   ];
    //   return view('admin.users', $data);
    // }
    //
    // // 用户管理
    // public function users_manage() {
    //   return view('admin.users_manage');
    // }
    //
    // // 管理提权
    // public function admins_manage() {
    //   $rights = DB::table('admin_rights_list')->get();
    //   $data = [
    //     'rights'  => $rights,
    //   ];
    //   if (!is_null($uid = request()->get('uid'))) {
    //     // 查询授权信息
    //     $have_rights = DB::table('admin_register')
    //                 ->join('admin_rights_list', 'admin_register.rid', '=', 'admin_rights_list.rid')
    //                 ->where('admin_register.uid', $uid)
    //                 ->select('admin_register.rid', 'admin_register.status', 'admin_rights_list.description')
    //                 ->get();
    //     if ($have_rights) {
    //       $data['have_rights'] = $have_rights;
    //     }
    //     // 查询管理等级
    //     $admin = DB::table('admin_level')->where('uid', $uid)->where('status', '<>', -1)->first();
    //     $admin_level = $admin ? $admin->level : '';
    //     $data['admin_level'] = $admin_level;
    //   }else{
    //     $data['admin_level'] = '';
    //   }
    //   return view('admin.admins_manage', $data);
    // }
    //
    // // 勋章一览
    // public function badges() {
    //   $activities = DB::table('badges')->paginate(25);
    //   $data = [
    //     'charts'  => $activities,
    //   ];
    //   return view('admin.badges', $data);
    // }
    //
    // // 勋章管理
    // public function badges_manage() {
    //   $effects = DB::table('effects')->get();
    //   $goods   = DB::table('shop')
    //           ->leftJoin('badges', 'badges.gid', '=', 'shop.gid')
    //           ->where('badges.gid', NULL)
    //           ->where('shop.tid', 1)
    //           ->select('shop.gid', 'shop.gname', 'shop.description')
    //           ->get();
    //   $data = [
    //     'effects'  => $effects,
    //     'goods'    => $goods,
    //   ];
    //   return view('admin.badges_manage', $data);
    // }
    //
    // // 勋章一览
    // public function effects() {
    //   $activities = DB::table('effects')->paginate(25);
    //   $data = [
    //     'charts'  => $activities,
    //   ];
    //   return view('admin.effects', $data);
    // }
    //
    // // 勋章一览
    // public function effects_manage() {
    //   $activities = DB::table('effects')->paginate(25);
    //   $data = [
    //     'charts'  => $activities,
    //   ];
    //   return view('admin.effects_manage', $data);
    // }
}
