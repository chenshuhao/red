{% include "layout/header.volt" %}

<section class="rt_wrap content mCustomScrollbar">
    <div class="rt_content">
        <h1>{{title}}</h1>
        <p>3秒后自动跳转...</p>
        <div>
</section>
</div>

<script>
    setTimeout(function(){
        window.location.href = '{{url}}';
    },3000);
</script>

{% include "layout/footer.volt" %}