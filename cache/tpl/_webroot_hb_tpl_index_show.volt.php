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
<section>
	<h2><strong style="color:grey;"></strong></h2>
	<div class="page_title">
		<h2 class="fl">红包系统用户列表</h2>
	</div>
	<table class="table">
		<tr>
			<th>用户名ID(UID)</th>
			<th>用户名</th>
			<th>类型</th>
			<th>创建时间</th>
			<th>最后登入时间</th>
			<th>有效期</th>
			<th>公众号类型</th>
			<th>状态</th>
			<th>操作</th>
		</tr>

		<?php foreach ($list['data'] as $item) { ?>
			<tr>
				<td><?php echo $item['id']; ?></td>
				<td><?php echo $item['username']; ?></td>
				<td><?php echo $item['type']; ?></td>
				<td><?php echo $item['time']; ?></td>
				<td><?php echo $item['last_time']; ?></td>
				<td><?php echo $item['stime']; ?> - <?php echo $item['etime']; ?></td>
				<td><?php echo $item['gzh']; ?></td>
				<td><?php echo $item['status']; ?></td>
				<td>
					<a href="#" class="inner_btn">禁用</a>
					<a href="/admin/shenhe?uid=<?php echo $item['id']; ?>" class="inner_btn">活动审核(代发)</a>
				</td>
			</tr>
		<?php } ?>
	</table>
	<aside class="paging">
		<a href="?page=<?php echo $list['current'] - 1; ?>">上一页</a>
		<a href="?page=<?php echo $list['next']; ?>">下一页</a>
	</aside>
</section>
		</section>
</div>
</body>
</html>