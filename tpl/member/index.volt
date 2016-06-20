{% include "layout/header.volt" %}

<section class="rt_wrap content mCustomScrollbar">
    <div class="rt_content">

<form action="" method="post">
    <h2><strong style="color:grey;">修改密码</strong></h2>
    <ul class="ulColumn2">
        <li>
            <span class="item_name" style="width:120px;">原密码：</span>
            <input type="password" class="textbox textbox_295" name="oldmima" placeholder="原密码"/>
        </li>
        <li>
            <span class="item_name" style="width:120px;">新密码：</span>
            <input type="text" class="textbox textbox_295" name="password" placeholder="密码"/>
        </li>
        <li>
            <span class="item_name" style="width:120px;"></span>
            <input type="submit" class="link_btn" value="修改密码"/>
        </li>
    </ul>

</form>
        </div>
    </section>


{% include "layout/footer.volt" %}