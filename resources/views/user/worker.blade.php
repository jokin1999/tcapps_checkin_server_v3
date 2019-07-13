@extends('user/master')
@section('header')
Worker管理
@endsection
@section('body')
<!-- 基本信息 -->
<div class="alert alert-primary">
  Worker是此游戏最基础、最重要的部分，玩家需要充分理解Worker的运行机制才能获得最高收益。机制详解请移步左侧查看游戏手册->Worker部分。
</div>
<div class="row">
  <div class="col-md-4 col-sm-12 mb-3 text-center">
    <div class="card border-dark mb-3">
      <div class="card-header">
        基本信息
      </div>
      <div class="card-body text-dark">
        <div class="row">
          <!-- UID -->
          <div class="col-6 text-right mb-1 font-weight-bold">兑换券：</div>
          <div class="col-6 text-left mb-1">{{ $worker_ticket }} @if($worker_ticket > 0) <a href="javascript:redeem();">兑换</a> @endif</div>
          <!-- 积分 -->
          <div class="col-6 text-right mb-1 font-weight-bold">Worker：</div>
          <div class="col-6 text-left mb-1">{{ $worker_count }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- 产区 -->
<div class="row">
  @foreach($field as $f)
  <div class="col-12 col-md-6 col-lg-3">
    <div class="card card-dark">
      <div class="card-header">
        <h4>{{$f->fname}}</h4>
        <div class="card-header-action">
          <button onclick="javascript:query_modal({{ $f->fid }});" class="btn btn-info" id="btn_query_{{ $f->fid }}"> <i class="fa fa-fw fa-tools"></i> </button>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6 text-right mb-1 font-weight-bold">
            <strong>产出资源：</strong>
          </div>
          <div class="col-6 text-left mb-1">
            {{ $f->iname }}
          </div>
          <div class="col-6 text-right mb-1 font-weight-boldcol-6 text-right mb-1 font-weight-bold">
            <strong>产出速度：</strong>
          </div>
          <div class="col-6 text-left mb-1">
            {{ $f->speed }}/h
          </div>
          <div class="col-6 text-right mb-1 font-weight-boldcol-6 text-right mb-1 font-weight-bold">
            <strong>产出倍率：</strong>
          </div>
          <div class="col-6 text-left mb-1">
            {{ $f->times }} 倍
          </div>
          <div class="col-6 text-right mb-1 font-weight-boldcol-6 text-right mb-1 font-weight-bold">
            <strong>数量限制：</strong>
          </div>
          <div class="col-6 text-left mb-1">
            @if ($f->limi_count === 0)
              无限制
            @else
              <span class="text-danger font-weight-bold">{{ $f->limi_count }}</span>
            @endif
          </div>
          <div class="col-6 text-right mb-1 font-weight-boldcol-6 text-right mb-1 font-weight-bold">
            <strong>最低等级：</strong>
          </div>
          <div class="col-6 text-left mb-1">
            {{ $f->limi_level }} 级
          </div>
          <div class="col-6 text-right mb-1 font-weight-boldcol-6 text-right mb-1 font-weight-bold">
            <strong>Worker总数：</strong>
          </div>
          <div class="col-6 text-left mb-1">
            {{ isset($field_workers[$f->fid]) ? $field_workers[$f->fid] : 0 }}
          </div>
          <div class="col-6 text-right mb-1 font-weight-boldcol-6 text-right mb-1 font-weight-bold">
            <strong>我的Worker：</strong>
          </div>
          <div class="col-6 text-left mb-1">
            {{ isset($field_workers_mine[$f->fid]) ? $field_workers_mine[$f->fid] : 0 }}
          </div>
        </div>
      </div>
      <div class="card-footer text-center">
        <a href="javascript:assign_modal({{ $f->fid }});" class="btn btn-primary" id="btn_assign_{{ $f->fid }}">投入</a>
        <a href="javascript:query_harvest({{ $f->fid }});" class="btn btn-success" id="btn_harvest_{{ $f->fid }}">收获</a>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endsection
@section('extraModalContent')
<!-- 投入 -->
<div class="modal fade" id="_assign" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="_assign_title">投入矿区</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="_assign_body">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th scope="col">WID</th>
              <th scope="col">等级</th>
              <th scope="col">更新</th>
              <th scope="col">操作</th>
            </tr>
          </thead>
          <tbody id="_assign_body_table">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- 查询 -->
<div class="modal fade" id="_query" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="_query_title">查看矿区</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="_query_body">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th scope="col">WID</th>
              <th scope="col">等级</th>
              <th scope="col">更新</th>
              <th scope="col">操作</th>
            </tr>
          </thead>
          <tbody id="_query_body_table">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- 收获 -->
<div class="modal fade" tabindex="-1" role="dialog" id="_harvest">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">收获确认</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>
          <strong>操作区域：</strong> <span id="_harvest_field">-</span> <br />
          <strong>区域名称：</strong> <span id="_harvest_field_name">-</span> <br />
          <strong>产出速度：</strong> <span id="_harvest_field_speed">-</span> <br />
          <strong>产出倍率：</strong> <span id="_harvest_field_times">-</span> <br />
          <strong>Worker总数：</strong> <span id="_harvest_worker_count">-</span> <br />
          <strong>开始时间：</strong> <span id="_harvest_update_time">-</span> <br />
          <strong>时间小计：</strong> <span id="_harvest_audit_time">-</span> 小时<br />
        </p>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">验证码</span>
          </div>
          <input type="text" class="form-control" placeholder="Captcha" id="captcha" maxlength="6">
          <div class="input-group-append">
            <img src="{{ captcha_src() }}" alt="captcha" onclick="this.src='{{ captcha_src() }}' + Math.random();" id="captcha_img">
          </div>
        </div>
        <p>
          <strong>本次收获预计到账 <span id="_harvest_anticipate_count">0</span> <span id="_harvest_iname">-</span></strong>
        </p>
        <p class="text-mutes">
          *确认收获视为自动放弃未满1小时或超过24小时部分收益。
          <br>
          *预计到账与实际到账可能略有差异，以实际到账为准
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="javascript:;" id="btn_harvest_comfirm">确认收获</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/template" id="tpl_assign_table">
<tr id="worker_assign_table_==6==">
  <th scope="row">==1==</th>
  <td>==2==</td>
  <td>==3==</td>
  <td><a href="javascript:;" onclick="javascript:assign(==4==, ==5==);" id="worker_assign_btn_==7==">投入</a></td>
</tr>
</script>
<script type="text/template" id="tpl_query_table">
<tr id="worker_query_table_==6==">
  <th scope="row">==1==</th>
  <td>==2==</td>
  <td>==3==</td>
  <td><a class="text-danger" href="javascript:;" onclick="javascript:withdraw(==4==);" id="worker_withdraw_btn_==7==">撤出</a></td>
</tr>
</script>
<script type="text/javascript">
  function assign_modal(fid) {
    iziToast.info({
      id: 'loading-workers',
      message: '请稍候，加载Workers中...',
      position: 'topRight',
      timeout: 10000
    });
    $('#btn_assign_' + fid).attr('disabled', 'disabled');
    $.ajax({
      url: '/api/worker',
      type: 'post',
      dataType: 'json',
      data: {
        'fid': 0
      },
      timeout: 10000,
      complete: function(XMLHttpRequest, status){
        iziToast.hide({}, document.querySelector('#loading-workers'));
        $('#btn_assign_' + fid).removeAttr('disabled');
        if (status === 'timeout') {
          m_alert('响应超时，请稍候再试！');
        }
      },
      success: function(data){
        if (data.errno === 0) {
          if( data.body.data.length === 0) {
            m_alert('无空闲Worker，请撤出后再投入！');
            return false;
          }
          let workers = data.body.data;
          let node = $('#_assign_body_table');
          node.text('');
          $.each(workers, function(key, worker) {
            console.log(worker);
            let tpl = $('#tpl_assign_table').text();
            tpl = tpl.replace('==1==', worker.wid);
            tpl = tpl.replace('==2==', worker.level);
            tpl = tpl.replace('==3==', worker.update_time);
            tpl = tpl.replace('==4==', worker.wid);
            tpl = tpl.replace('==5==', fid);
            tpl = tpl.replace('==6==', worker.wid);
            tpl = tpl.replace('==7==', worker.wid);
            node.append(tpl);
          });
          $('#_assign').modal();
        }else{
          if (data.errno === 4301) {
            m_alert('查找失败，请稍候再试！');
          }else{
            m_alert('网络状态不佳，请稍候再试！');
          }
        }
      }
    });
  }
  function assign(wid, fid) {
    iziToast.info({
      id: 'loading-workers-assign',
      message: '请稍候，投放中...',
      position: 'topRight',
      timeout: 10000
    });
    // 清除按钮效果
    let btn_attr_onclick = $('#worker_assign_btn_' + wid).attr('onclick');
    $('#worker_assign_btn_' + wid).attr('onclick', '');
    $.ajax({
      url: '/api/worker/assign',
      type: 'post',
      dataType: 'json',
      data: {
        'wid': wid,
        'fid': fid
      },
      timeout: 10000,
      complete: function(XMLHttpRequest, status){
        iziToast.hide({}, document.querySelector('#loading-workers-assign'));
        $('#worker_assign_btn_' + wid).attr('onclick', btn_attr_onclick);
        if (status === 'timeout') {
          iziToast.danger({
            id: 'loading-workers',
            message: '响应超时，请稍候再试！',
            position: 'topRight',
            timeout: 10000
          });
        }
      },
      success: function(data){
        if (data.errno === 0) {
          iziToast.info({
            message: '分配Worker(wid:' + wid + ')成功！',
            position: 'topRight',
            timeout: 10000
          });
          $('#worker_assign_table_' + wid).remove();
        }else{
          if (data.errno === 4401) {
            iziToast.danger({
              message: '此Worker暂时无法进行分配，请稍候再试！',
              position: 'topRight',
              timeout: 10000
            });
          }else if(data.errno === 4402){
            iziToast.warning({
              message: '该产区异常！',
              position: 'topRight',
              timeout: 10000
            });
          }else if(data.errno === 4403){
            iziToast.warning({
              message: '该Worker等级不足，无法投入！',
              position: 'topRight',
              timeout: 10000
            });
          }else{
            iziToast.warning({
              message: '未知错误！',
              position: 'topRight',
              timeout: 10000
            });
          }
        }
      }
    });
  }
  function query_modal(fid) {
    iziToast.info({
      id: 'loading-workers',
      message: '请稍候，加载Workers中...',
      position: 'topRight',
      timeout: 10000
    });
    $('#btn_query_' + fid).attr('disabled', 'disabled');
    $.ajax({
      url: '/api/worker',
      type: 'post',
      dataType: 'json',
      data: {
        'fid': fid
      },
      timeout: 10000,
      complete: function(XMLHttpRequest, status){
        iziToast.hide({}, document.querySelector('#loading-workers'));
        $('#btn_query_' + fid).removeAttr('disabled');
        if (status === 'timeout') {
          m_alert('响应超时，请稍候再试！');
        }
      },
      success: function(data){
        if (data.errno === 0) {
          let workers = data.body.data;
          let node = $('#_query_body_table');
          node.text('');
          $.each(workers, function(key, worker) {
            console.log(worker);
            let tpl = $('#tpl_query_table').text();
            tpl = tpl.replace('==1==', worker.wid);
            tpl = tpl.replace('==2==', worker.level);
            tpl = tpl.replace('==3==', worker.update_time);
            tpl = tpl.replace('==4==', worker.wid);
            tpl = tpl.replace('==6==', worker.wid);
            tpl = tpl.replace('==7==', worker.wid);
            node.append(tpl);
          });
          $('#_query').modal();
        }else{
          if (data.errno === 4301) {
            m_alert('查找失败，请稍候再试！');
          }else{
            m_alert('网络状态不佳，请稍候再试！');
          }
        }
      }
    });
  }
  function withdraw(wid) {
    iziToast.info({
      id: 'loading-workers-assign',
      message: '请稍候，投放中...',
      position: 'topRight',
      timeout: 10000
    });
    // 清除按钮效果
    let btn_attr_onclick = $('#worker_withdraw_btn_' + wid).attr('onclick');
    $('#worker_withdraw_btn_' + wid).attr('onclick', '');
    $.ajax({
      url: '/api/worker/withdraw',
      type: 'post',
      dataType: 'json',
      data: {
        'wid': wid
      },
      timeout: 10000,
      complete: function(XMLHttpRequest, status){
        iziToast.hide({}, document.querySelector('#loading-workers-assign'));
        $('#worker_withdraw_btn_' + wid).attr('onclick', btn_attr_onclick);
        if (status === 'timeout') {
          iziToast.danger({
            id: 'loading-workers',
            message: '响应超时，请稍候再试！',
            position: 'topRight',
            timeout: 10000
          });
        }
      },
      success: function(data){
        if (data.errno === 0) {
          iziToast.info({
            message: '撤出Worker(wid:' + wid + ')成功！',
            position: 'topRight',
            timeout: 10000
          });
          $('#worker_query_table_' + wid).remove();
        }else{
          if (data.errno === 4501) {
            iziToast.danger({
              message: '此Worker暂时无法进行撤出，请稍候再试！',
              position: 'topRight',
              timeout: 10000
            });
          }else{
            iziToast.warning({
              message: '未知错误！',
              position: 'topRight',
              timeout: 10000
            });
          }
        }
      }
    });
  }
  function query_harvest(fid) {
    // 刷新验证码
    $('#captcha_img').click();
    $('#captcha').val('');
    iziToast.info({
      id: 'loading-fields',
      message: '请稍候，加载区域信息中...',
      position: 'topRight',
      timeout: 10000
    });
    $('#btn_harvest_' + fid).attr('disabled', 'disabled');
    $.ajax({
      url: '/api/worker/harvest_query',
      type: 'post',
      dataType: 'json',
      data: {
        'fid': fid
      },
      timeout: 10000,
      complete: function(XMLHttpRequest, status){
        iziToast.hide({}, document.querySelector('#loading-fields'));
        $('#btn_harvest_' + fid).removeAttr('disabled');
        if (status === 'timeout') {
          m_alert('响应超时，请稍候再试！');
        }
      },
      success: function(data){
        if (data.errno === 0) {
          let workers = data.body.data;
          $('#_harvest_field').text(data.body.data.field_info.fid);
          $('#_harvest_field_name').text(data.body.data.field_info.fname);
          $('#_harvest_field_speed').text(data.body.data.field_info.speed);
          $('#_harvest_field_times').text(data.body.data.field_info.times);
          $('#_harvest_worker_count').text(data.body.data.worker_count);
          $('#_harvest_update_time').text(data.body.data.update_time);
          let time_delta_sec = $.now() / 1000 - data.body.data.update_time_unix;
          let time_hr = Math.floor(time_delta_sec / 60 / 60);
          time_hr = time_hr > 24 ? 24 : time_hr;
          $('#_harvest_audit_time').text(time_hr);
          $('#_harvest_iname').text(data.body.data.field_info.iname);
          $('#_harvest_anticipate_count').text(Math.floor(data.body.data.field_info.speed * data.body.data.field_info.times * data.body.data.worker_count * time_hr));
          $('#btn_harvest_comfirm').attr('onclick', 'javascript:harvest(' + fid + ');');
          $('#_harvest').modal({
            backdrop: 'static'
          });
        }else{
          if (data.errno === 4601) {
            m_alert('获取该区域信息失败，请稍候再试！');
          }else{
            m_alert('网络状态不佳，请稍候再试！');
          }
        }
      }
    });
  }
  function harvest(fid) {
    $('#_harvest').modal('hide');
    let captcha  = $('#captcha').val();
    if (captcha === '') {
      m_alert('验证码错误', 'warning');
      return false;
    }
    m_loading();
    $.ajax({
      url: '/api/worker/harvest',
      type: 'post',
      dataType: 'json',
      data: {
        'fid': fid,
        'captcha': captcha
      },
      timeout: 10000,
      complete: function(XMLHttpRequest, status){
        m_loading(false);
        if (status === 'timeout') {
          m_alert('响应超时，请稍候再试！', 'danger');
        }
      },
      success: function(data){
        if (data.errno === 0) {
          m_alert('成功收获 ' + data.body.data.profits + ' ' + data.body.data.iname, 'success');
        }else{
          if (data.errno === 4701) {
            m_alert('获取该区域信息失败，请稍候再试！', 'danger');
          }else if(data.errno === 4702){
            m_alert('发放收益失败，请稍候再试！', 'danger');
          }else if(data.errno === 4703){
            m_alert('验证码错误', 'warning');
          }else{
            m_alert('网络情况不佳，请稍候再试！', 'danger');
          }
        }
      }
    });
  }
  function redeem() {
    m_loading();
    $.getJSON('/api/worker/redeem', function(data){
      m_loading(false);
      if (data.errno === 0) {
        m_alert('兑换成功！', 'success');
      }else{
        let info = '兑换失败！请刷新页面后重试！';
        if (data.errno === 4201) {
          info = '兑换券数量不足！';
        }else if (data.errno === 4202) {
          info = '扣除兑换券失败，请联系管理员追回兑换券！';
        }else if (data.errno === 4203) {
          info = '注册Worker失败，请联系管理员追回兑换券！';
        }
        m_alert(info, 'danger');
      }
    });
  }
</script>
@endsection
