{% include "layout/header.volt" %}


<section class="rt_wrap content mCustomScrollbar">
    <div class="rt_content">
        <section>
            <h2><strong style="color:grey;"></strong></h2>
            <div class="page_title">
                <h2 class="fl">活动</h2>
            </div>
            <table class="table">
                <tr>
                    <th>#</th>
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
                    <th>审核状态</th>
                    <th>操作</th>
                </tr>

                {% for item in list %}
                <tr>
                    <td>{{item['id']}}</td>
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
                    <td>{{item['d_status']?'审核通过':'未审核'}}</td>
                    <td>
                        <a onclick="sta({{item['id']}})" class="inner_btn">通过审核/禁止</a>
                        <a href="/admin/data?vid={{item['id']}}" class="inner_btn">数据统计</a>
                        <a href="/admin/mapData?vid={{item['id']}}" class="inner_btn">地图概况</a>
                    </td>
                </tr>
                {% endfor %}
            </table>
        </section>
</section>
</div>
<script>
    function sta(id){
        $.getJSON('/admin/sheheok',{id:id},function(ret){
            alert(ret.error_reason);
            location.reload(true);
        });
    }
</script>

{% include "layout/footer.volt" %}