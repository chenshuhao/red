<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>后台</title>
<meta name="author" content="DeathGhost" />
<link rel="stylesheet" type="text/css" href="/css/style.css" />
<!--[if lt IE 9]>
<script src="/js/html5.js"></script>
<![endif]-->
<script src="/js/jquery.js"></script>
<script src="/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script>
  (function($){
    $(window).load(function(){

      $("a[rel='load-content']").click(function(e){
        e.preventDefault();
        var url=$(this).attr("href");
        $.get(url,function(data){
          $(".content .mCSB_container").append(data); //load new content inside .mCSB_container
          //scroll-to appended content
          $(".content").mCustomScrollbar("scrollTo","h2:last");
        });
      });

      $(".content").delegate("a[href='top']","click",function(e){
        e.preventDefault();
        $(".content").mCustomScrollbar("scrollTo",$(this).attr("href"));
      });

    });
  })(jQuery);
</script>
</head>
<body>
<!--header-->
<header>
  <h1><img src="/images/admin_logo.png"/></h1>
  <ul class="rt_nav">
    <li><a href="#" class="admin_icon"><?php echo $username; ?></a></li>
    <li><a href="/admin/changepassword" class="set_icon">修改密码</a></li>
    <li><a href="/admin/loginout" class="quit_icon">安全退出</a></li>
  </ul>
</header>

<!--aside nav-->
<aside class="lt_aside_nav content mCustomScrollbar">
  <ul>
    <?php if ($UT == 'admin') { ?>
      <li>
        <dl>
          <dt>用户管理</dt>
          <!--当前链接则添加class:active-->
          <dd><a href="/admin/index" class="<?php if ($action == 'list') { ?> active <?php } ?>"> 用户列表</a></dd>
          <dd><a href="/admin/add" class="<?php if ($action == 'add') { ?> active <?php } ?>">添加用户</a></dd>
        </dl>
    </li>
    <?php } else { ?>
      <li>
        <dl>
          <dt>红包管理</dt>
          <dd><a href="/admin/vlist" class="<?php if ($action == 'vlist') { ?> active <?php } ?>"> 活动列表</a></dd>
          <dd><a href="/admin/vadd" class="<?php if ($action == 'vadd') { ?> active <?php } ?>">添加活动</a></dd>
        </dl>
    </li>
    <!--<li>-->
      <!--<dl>-->
        <!--<dt>红包统计</dt>-->
        <!--<dd><a href="/admin/data" class="<?php if ($action == 'data') { ?> active <?php } ?>"> 统计数据</a></dd>-->
        <!--&lt;!&ndash;<dd><a href="/admin/vadd" class="<?php if ($action == 'vadd') { ?> active <?php } ?>">社交关系</a></dd>&ndash;&gt;-->
      <!--</dl>-->
    <!--</li>-->
      <li>
      <dl>
        <dt>配置</dt>
        <dd><a href="/admin/config" class="<?php if ($action == 'pay') { ?> active <?php } ?>"> 支付配置</a></dd>
      </dl>
    </li>
    <?php } ?>
  </ul>
</aside>
<script src='/laydate/laydate.js' ></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<section class="rt_wrap content mCustomScrollbar">
  <div class="rt_content">
  <form method='post' enctype="multipart/form-data">
    <section>
      <h2><strong style="color:grey;">添加现金红包</strong></h2>
      <?php if ($voteinfo->id) { ?>
        <input type="hidden" class="textbox textbox_295" name="id" value="<?php echo $voteinfo->id; ?>"/>
      <?php } ?>

      <ul class="ulColumn2">
        <li>
          <span class="item_name" style="width:120px;">活动名称：</span>
          <input type="text" class="textbox textbox_295" name="name" placeholder="活动名称,10个字符" value="<?php echo $voteinfo->name; ?>"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">领红包关键词：</span>
          <input type="text" class="textbox textbox_295" placeholder="红包关键词" name="key" value="<?php echo $voteinfo->key; ?>"/>
            <span class="errorTips">(请勿在其他地方重复使用，否则将无法发送红包。)</span>
        </li>
        <li>
          <span class="item_name" style="width:120px;">活动开始时间：</span>
          <input type="text" class="textbox textbox_295" placeholder="请选择开始时间" name='stime'  class="laydate-icon" value="<?php echo $voteinfo->stime; ?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">活动结束时间：</span>
          <input type="text" class="textbox textbox_295" placeholder="请选择开始时间" name='etime'  class="laydate-icon" value="<?php echo $voteinfo->etime; ?>" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">活动预算金额：</span>
          <input type="text" class="textbox textbox_295" placeholder="" name='money' value="<?php echo $voteinfo->money; ?>" />
        </li>
          <li>
          <span class="item_name" style="width:120px;">每人可领取次数：</span>
          <input type="text" class="textbox textbox_295" placeholder="" name='num' value="<?php echo $voteinfo->num; ?>" />
        </li>
          <li>
          <span class="item_name" style="width:120px;">禁止领取时间段：</span>
          <input type="text" class="textbox textbox_295" placeholder="禁止领取时间开始  0-23 时" name='sd'  value="<?php echo $voteinfo->sd; ?>"/>
          -
          <input type="text" class="textbox textbox_295" placeholder="禁止领取时间结束  0-23 时" name='ed' value="<?php echo $voteinfo->ed; ?>"/>
          <span class="errorTips">((该时间段内不能领取红包,默认0-0时,全天可领))</span>
        </li>
          <li>
          <span class="item_name" style="width:120px;">红包金额起始值：</span>
          <input type="text" class="textbox textbox_295" placeholder="不得小于1元" name='smoeny'  value="<?php echo $voteinfo->smoeny; ?>"/>
          -
          <input type="text" class="textbox textbox_295" placeholder="不得大于200元" name='emoney' value="<?php echo $voteinfo->emoney; ?>"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">企业名称：</span>
          <input type="text" class="textbox textbox_295" placeholder="8个字以内" name='qname' value="<?php echo $voteinfo->qname; ?>"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">红包祝福语：</span>
          <input type="text" class="textbox textbox_295" placeholder="20个字以内" name='wsing' value="<?php echo $voteinfo->wsing; ?>"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">可领红包范围：</span>
          <input type="text" class="textbox textbox_295" placeholder="公里" name='fanwei' value="<?php echo $voteinfo->fanwei; ?>">
          <span class="errorTips">(为0 则不限制范围)</span>
        </li>
        <li>
          <span class="item_name" style="width:120px;">中心点选择：</span>
            <input type="text" class="textbox textbox_295"  id="dizhi"> <a onclick="codeAddress()" class="link_btn" >定位</a>
            <div id="container" style='height:300px;width:500px;    margin-left: 128px;'></div>
            <div style="height:22px;"></div>
            <div id="fitBoundsDiv"></div>
            <div id="centerDiv" style="margin-left:128px;"></div>
            <div id="zoomDiv"></div>
            <div id="containerDiv"></div>
            <div id="mapTypeIdDiv"></div>
            <div id="projection"></div>
              <input type="hidden" class="textbox textbox_295" id='lt' name='lt' value="<?php echo $voteinfo->lt; ?>">

        </li>

        <li>
          <span class="item_name" style="width:120px;">红包图片：</span>
          <label class="uploadImg">
            <input type="file" name='tup'/>
            <span>上传图片</span>
          </label>

          <img src="/<?php echo ($voteinfo->tup ? $voteinfo->tup : 'images/default/hb_1.jpg'); ?>" width="300px">

          <span class="errorTips">(若您是订阅号,请直接上传带二维码的推广图片,推广图最佳尺寸640x1006)</span>
        </li>
        <li>
          <span class="item_name" style="width:120px;"></span>
          <input type="submit" class="link_btn"/>
        </li>
      </ul>
      </section>
  </form>
  </div>
</section>
  <script type="text/javascript">
    function init() {

      //div容器
      var container = document.getElementById("container");
      var centerDiv = document.getElementById("centerDiv");


      <?php if ($voteinfo->lt) { ?>
      //初始化地图
      var map = new qq.maps.Map(container, {
        // 地图的中心地理坐标
        center: new qq.maps.LatLng(<?php echo $voteinfo->lt; ?>),
        zoom: 13
      });
      <?php } else { ?>
      //初始化地图
      var map = new qq.maps.Map(container, {
        // 地图的中心地理坐标
        center: new qq.maps.LatLng(39.916527, 116.397128),
        zoom: 13
      });

      //获取城市列表接口设置中心点
      var citylocation = new qq.maps.CityService({
        complete : function(result){
          map.setCenter(result.detail.latLng);
        }
      });
      //根据用户IP查询城市信息。
      citylocation.searchLocalCity();

      <?php } ?>


      //调用地址解析类
      geocoder = new qq.maps.Geocoder({
        complete : function(result){
          map.setCenter(result.detail.location);
          $('#lt').val(map.getCenter());
        }
      });

        var middleControl = document.createElement("div");
        middleControl.style.left="232px";
        middleControl.style.top="132px";
        middleControl.style.position="relative";
        middleControl.style.width="36px";
        middleControl.style.height="36px";
        middleControl.style.zIndex="100000";
        middleControl.innerHTML ='<img src="https://www.cdlhome.com.sg/mobile_assets/images/icon-location.png" />';
        document.getElementById("container").appendChild(middleControl);
      //返回地图当前中心点地理坐标
      centerDiv.innerHTML = "latlng:" + map.getCenter();
      //当地图中心属性更改时触发事件
      qq.maps.event.addListener(map, 'center_changed', function() {
        centerDiv.innerHTML = "latlng:" + map.getCenter();
        $('#lt').val(map.getCenter());
      });
      }

      init();

      function codeAddress() {
        var address = document.getElementById("dizhi").value;
        //通过getLocation();方法获取位置信息值
        geocoder.getLocation(address);
      }
    </script>

</body>
</html>
