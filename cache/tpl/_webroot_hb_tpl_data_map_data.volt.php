
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

<section class="rt_wrap content mCustomScrollbar">
    <div class="rt_content">
        <div class="page_title">
            <h2 class="fl"><?php echo $vinfo->name; ?>活动地图数据</h2>
        </div>
        <div style="width: 100%;height: 800px;" id="container">

        </div>
    </div>

</section>

<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>

<script>

    var list = <?php echo $list; ?>;
    console.dir(list);
    function init() {
        var map = new qq.maps.Map(document.getElementById("container"), {
            center: new qq.maps.LatLng(<?php echo $vinfo->lt; ?>),
            zoom: 15
        });

        //创建一个Marker
        var marker = new qq.maps.Marker({
            //设置Marker的位置坐标
            position: new qq.maps.LatLng(<?php echo $vinfo->lt; ?>),
            //设置显示Marker的地图
            map: map
        });

        //设置Marker的可见性，为true时可见,false时不可见，默认属性为true
        marker.setVisible(true);
        //设置Marker的动画属性为从落下
        marker.setAnimation(qq.maps.MarkerAnimation.DOWN);
        ////设置Marker自定义图标的属性，size是图标尺寸，该尺寸为显示图标的实际尺寸，origin是切图坐标，该坐标是相对于图片左上角默认为（0,0）的相对像素坐标，anchor是锚点坐标，描述经纬度点对应图标中的位置
        var anchor = new qq.maps.Point(0, 39),
                size = new qq.maps.Size(30, 30),
                origin = new qq.maps.Point(0, 0),
                icon = new qq.maps.MarkerImage(
                        "/images/icon/shop-icon.png",
                        size,
                        origin,
                        anchor
                );
        marker.setIcon(icon);

        for(var i=0;i<list.length;i++){
            setMarker(list[i],map);
        }
    }

    init();


    function setMarker(item,map){


        var marker = new qq.maps.Marker({
            //设置Marker的位置坐标
            position: new qq.maps.LatLng(item.lat,item.lng),
            //设置显示Marker的地图
            map: map
        });

        //设置Marker的可见性，为true时可见,false时不可见，默认属性为true
        marker.setVisible(true);
        //设置Marker的动画属性为从落下
        marker.setAnimation(qq.maps.MarkerAnimation.DOWN);
            


//        //设置Marker阴影图片属性，size是图标尺寸，该尺寸为显示图标的实际尺寸，origin是切图坐标，该坐标是相对于图片左上角默认为（0,0）的相对像素坐标，anchor是锚点坐标，描述经纬度点对应图标中的位置
//        var anchorb = new qq.maps.Point(3, -30),
//                sizeb = new qq.maps.Size(42, 11),
//                origin = new qq.maps.Point(0, 0),
//                iconb = new qq.maps.MarkerImage(
//                        "http://open.map.qq.com/doc/img/nilb.png",
//                        sizeb,
//                        origin,
//                        anchorb
//                );
//
//        //添加信息窗口
        var info = new qq.maps.InfoWindow({
            map: map
        });
        qq.maps.event.addListener(marker, 'click', function() {
            info.open();
            info.setContent('<img src="'+item.headimgurl+'" width="50" height="50" />'+item.nickname+'<br><br><br><span style="font-size: 16px;color:red">得到红包'+item.price/100+'元</span>');
            info.setPosition(marker.getPosition());
        });
//
//        //获取标记的可拖动属性
//        info.open();
//        info.setContent('<img src="'+item.headimgurl+'" width="50" height="50" />'+item.nickname);
//        info.setPosition(marker.getPosition());
    }
</script>


</body>
</html>


