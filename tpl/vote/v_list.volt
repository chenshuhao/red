{% include "layout/header.volt" %}

<section class="rt_wrap content mCustomScrollbar">
	<div class="rt_content">
		<section>
			<h2><strong style="color:grey;"></strong></h2>
			<div class="page_title">
				<h2 class="fl">红包系统用户列表</h2>
			</div>
			<table class="table">
				<tr>
					<th>活动名称</th>
					<th>活动有效期</th>
					<th>活动预算(元)</th>
					<th>活动可领时段</th>
					<th>已领金额(元)</th>
					<th>已领人数</th>
					<th>每人领取次数</th>
					<th>红包金额范围</th>
					<th>领取范围(公里)</th>
					<th>状态</th>
					<th>操作</th>
				</tr>

				{% for item in list['data'] %}
				<tr>
					<td>{{item['name']}}</td>
					<td>{{item['stime']}}</td>
					<td>{{item['money']}}</td>
					<td>{{item['sd']}}</td>
					<td>{{item['lingjine']}}</td>
					<td>{{item['lingrenshu']}}</td>
					<td>{{item['num']}}</td>
					<td>{{item['mfanwei']}}</td>
					<td>{{item['fanwei']}}</td>
					<td>{{item['status']?'正常':'未开启'}}</td>
					<td>
						<!--<a href="#" class="inner_btn">红包概率</a>-->
						<a href="javascript:;" class="inner_btn" onclick="sta({{item['id']}})">启用/停止</a>
						<a href="/admin/vadd?voteid={{item['id']}}" class="inner_btn">编辑</a>
						<a href="/admin/data?vid={{item['id']}}" class="inner_btn">数据统计</a>
						<a href="/admin/mapData?vid={{item['id']}}" class="inner_btn">地图概况</a>
					</td>
				</tr>
				{% endfor %}
			</table>
			<aside class="paging">
				<a href="?page={{list['current'] -1}}">上一页</a>
				<a href="?page={{list['next']}}">下一页</a>
			</aside>
		</section>
</section>
</div>

<script>
	function sta(id){
		$.getJSON('/admin/vstatus',{id:id},function(ret){
			alert(ret.error_reason);
			location.reload(true);
		});
	}
</script>


{% include "layout/footer.volt" %}
