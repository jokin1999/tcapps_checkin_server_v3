@extends('user/master')
@section('header')
资源商城
@endsection
@section('body')
<div class="row">
  @if($goods)
  @foreach($goods as $key => $value)
  <div class="col-12 col-sm-6 col-md-6 col-lg-3">
    <article class="article article-style-b">
      <div class="article-header">
        <div class="article-image" data-background="{{ $value['image'] }}" style="background-size: contain;">
        </div>
        <div class="article-badge">
          @if($value['endtime'] !== '1970-01-01 00:00:00')
          <div class="article-badge-item bg-danger" data-toggle="tooltip" title="现在 - {{ $value['endtime'] }}"><i class="fa-fw fas fa-clock"></i> 限时</div>
          @endif
          @if($value['all_count'] !== 0)
          <div class="article-badge-item bg-warning"><i class="fa-fw fas fa-fire"></i> 限量</div>
          @endif
          @if($value['onsale'] === 1 && date('Y-m-d H:i:s') >= $value['sale_starttime'] && date('Y-m-d H:i:s') <= $value['sale_endtime'])
          <div class="article-badge-item bg-info" data-toggle="tooltip" title="{{ $value['sale_starttime'] }} - {{ $value['sale_endtime'] }}"><i class="fa-fw fas fa-coins"></i> 促销</div>
          @endif
          @if($value['endtime'] === '1970-01-01 00:00:00' && $value['all_count'] === 0)
          <!-- 日常销售 -->
          @endif
        </div>
      </div>
      <div class="article-details" style="height:100%;">
        <div class="article-title">
          <h2><a href="javascript:m_alert('描述：{{ $value['description'] }}');">{{ $value['iname'] }}</a></h2>
        </div>
        <p>
          <strong>商品单价：</strong>
          @if($value['onsale'] === 1 && date('Y-m-d H:i:s') >= $value['sale_starttime'] && date('Y-m-d H:i:s') <= $value['sale_endtime'])
            <s class="text-muted">{{ $value['cost'] }}</s> <strong>{{ $value['sale_cost'] }}</strong>
          @else
            {{ $value['cost'] }}
          @endif
          积分
          <br>
          <strong>剩余库存：</strong>
          @if($value['all_count'] !== 0)
            {{ $value['all_count']-$value['all_bought'] }}
          @else
            不限量
          @endif
        </p>
        <div class="article-cta">
          <div class="article-cta">
            @if($value['all_count'] !== 0 && $value['all_count']-$value['all_bought'] <= 0)
            <button type="button" name="button" class="btn btn-primary" disabled>已售罄</button>
            @elseif($value['rebuy'] !== 0 && $value['rebuy'] <= $value['user_bought'])
            <button type="button" name="button" class="btn btn-primary" disabled>已到达购买上限</button>
            @else
            <button type="button" name="button" class="btn btn-primary" onclick="javascript:purchase({{ $value['iid'] }});" id="btn_g_{{ $value['iid'] }}">购买 / Purchase</button>
            @endif
          </div>
        </div>
      </div>
    </article>
  </div>

  @endforeach
  @else
  <div class="col-md-12 col-sm-12 mb-3">
    <div class="alert alert-light alert-has-icon">
      <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
      <div class="alert-body">
        <div class="alert-title">老板别急~小店一会就开张！</div>
        我们正在努力备货中，请稍等...
      </div>
    </div>
  </div>
  @endif
</div>

@endsection
@section('script')
<script type="text/javascript">
  function purchase(gid) {
    $('#btn_g_' + gid).attr('disabled', 'disabled');
    m_loading();
    $.getJSON('/api/purchase/' + gid, function(data){
      m_loading(false);
      if (data.errno === 0) {
        m_alert('购买成功！', 'success');
        location.href = '';
      }else{
        let info = '购买失败！请刷新页面后重试！';
        if (data.errno === 2503) {
          info = '余额不足！';
        }
        if (data.errno === 2504) {
          info = '该商品已经停售！';
        }
        if (data.errno === 2505) {
          info = '该商品已售罄！';
        }
        if (data.errno === 2506) {
          info = '该商品的购买次数已经达到上限！';
        }
        m_alert(info, 'danger');
        $('#btn_g_' + gid).removeAttr('disabled');
      }
    });
  }
</script>
@endsection
