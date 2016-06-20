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
                    <th>#</th>
                    <th>openid</th>
                    <th>领取用户</th>
                    <th>领取金额</th>
                    <th>状态</th>
                    <th>消息</th>
                    <th>时间</th>
                </tr>

                {% for item in list %}
                <tr>
                    <td>{{item['id']}}</td>
                    <td>{{item['openid'].openid}}</td>
                    <td>{{item['openid'].nickname}}</td>
                    <td>{{item['price']}}</td>
                    <td>{{item['status']}}</td>
                    <td>{{item['msg']}}</td>
                    <td>{{item['time']}}</td>
                </tr>
                {% endfor %}
            </table>
            <aside class="paging">
                <a href="?vid=<?php echo $_GET['vid']?>&page=1">第一页</a>
                <a href="?vid=<?php echo $_GET['vid']?>&page={{pre}}">上一页</a>
                <a href="?vid=<?php echo $_GET['vid']?>&page={{next}}">下一页</a>
                <a href="?vid=<?php echo $_GET['vid']?>&page={{last}}">最后一页</a>
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