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