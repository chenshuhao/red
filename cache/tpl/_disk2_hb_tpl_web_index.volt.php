<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>领取红包</title>
    <link rel="stylesheet" href="/weui/dist/style/weui.css"/>
    <link rel="stylesheet" href="/weui/dist/example/example.css"/>
    <script src=" http://res.wx.qq.com/open/js/jweixin-1.1.0.js"></script>
    <script src="/weui/dist/example/zepto.min.js"></script>
    <script src="/weui/dist/example/router.min.js"></script>
    <script src="/weui/dist/example/example.js"></script>
   <?php if (!$error) { ?>
    <script>
       $(function(){
           $('#loadingToast').show();
           wx.config(<?php echo $jsConfig; ?>);
       })

       wx.ready(function(){
           wx.getLocation({
               type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
               success: function (res) {
                   var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                   var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                   $.getJSON('/red/lng?token=<?php echo $token; ?>',{latitude:latitude,longitude:longitude},function(){
                   });
                   $('#loadingToast').hide();
               },
               cancel:function(res){
                   alert('获取不到您的位置,无法为您发放红包!请同意获取,您可以再试一次');
                   wx.closeWindow();
               }
           });

           wx.onMenuShareTimeline({
               title: '我在这里抢到红包,赶紧过来领红包吧!真的哦!', // 分享标题
               link: 'http://www.lexiongmao.cn/red/qcode?vid=<?php echo $_GET['vid']?>', // 分享链接
               imgUrl: 'http://www.lexiongmao.cn/images/red.png', // 分享图标
               success: function () {
                   myAlert('成功','您还可以分享给朋友来领取哦!');
               },
               cancel: function () {
                   myAlert('失败','您这样不对哦,好东西大家一起来领嘛!');
               }
           });

           wx.onMenuShareAppMessage({
               title: '我在这里抢到红包,赶紧过来领红包吧!真的哦!', // 分享标题
               desc: '我在这里抢到红包,赶紧过来领红包吧!真的哦!', // 分享描述
               link: 'http://www.lexiongmao.cn/red/qcode?vid=<?php echo $_GET['vid']?>', // 分享链接
               imgUrl: 'http://www.lexiongmao.cn/images/red.png', // 分享图标
               type: 'link', // 分享类型,music、video或link，不填默认为link
               dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
               success: function () {
                   myAlert('成功','您还可以分享到朋友圈哦!');
               },
               cancel: function () {
                   myAlert('失败','您这样不对哦,好东西大家一起来领嘛!');
               }

           });

       });
    </script>
    <?php } ?>
</head>
<body class="msg">
<?php if ($error) { ?>

<!--提示错误-->
<div class="msg">
    <div class="weui_msg">
        <div class="weui_icon_area">
            <img src="/images/731cfe05gw1f0mhlwwcyrg20b40b40t6.gif" height="180">
         </div>
        <div class="weui_text_area">
            <h2 class="weui_msg_title"><?php echo $error_msg; ?></h2>
            <p class="weui_msg_desc">感谢您的参与哦！</p>
        </div>
        <div class="weui_extra_area">
            <a href="http://apis.map.qq.com/uri/v1/marker?marker=coord:<?php echo $lat; ?>,<?php echo $lng; ?>;title:商家位置;addr:商家位置&referer=myapp">查看商家位置</a>
            <a href="/jszc.html">技术支持:微天下</a>

        </div>
    </div>
</div>
<?php } else { ?>
<!--领取-->
    <div>
        <div class="msg">
            <div class="weui_msg">
                <div class="weui_icon_area">
                    <img src="/images/731cfe05gw1f0mhlwwcyrg20b40b40t6.gif" height="180">
                </div>
                <div class="weui_text_area">
                    <h2 class="weui_msg_title">感谢您的关注!</h2>
                    <p class="weui_msg_desc">赶紧抢红包吧,抢完之后还可以分享给您朋友来抢哦</p>
                </div>
                <div class="weui_opr_area">
                    <p class="weui_btn_area">
                        <a href="javascript:;" class="weui_btn weui_btn_primary" onclick="lingqu()">点击领取红包</a>
                    </p>
                </div>
                <div class="weui_extra_area">
                    <a href="http://apis.map.qq.com/uri/v1/marker?marker=coord:<?php echo $lat; ?>,<?php echo $lng; ?>;title:商家位置;addr:商家位置&referer=myapp">查看商家位置</a>
                    <a href="/jszc.html">技术支持:微天下</a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!--dilog-->
<!--<div class="weui_dialog_alert">-->
    <!--<div class="weui_mask"></div>-->
    <!--<div class="weui_dialog">-->
        <!--<div class="weui_dialog_hd"><strong class="weui_dialog_title">弹窗标题</strong></div>-->
        <!--<div class="weui_dialog_bd">弹窗内容，告知当前页面信息等</div>-->
        <!--<div class="weui_dialog_ft">-->
            <!--<a href="#" class="weui_btn_dialog primary">确定</a>-->
        <!--</div>-->
    <!--</div>-->
<!--</div>-->
<div id="loadingToast" class="weui_loading_toast" style="display: none;">
    <div class="weui_mask_transparent"></div>
    <div class="weui_toast">
        <div class="weui_loading">
            <div class="weui_loading_leaf weui_loading_leaf_0"></div>
            <div class="weui_loading_leaf weui_loading_leaf_1"></div>
            <div class="weui_loading_leaf weui_loading_leaf_2"></div>
            <div class="weui_loading_leaf weui_loading_leaf_3"></div>
            <div class="weui_loading_leaf weui_loading_leaf_4"></div>
            <div class="weui_loading_leaf weui_loading_leaf_5"></div>
            <div class="weui_loading_leaf weui_loading_leaf_6"></div>
            <div class="weui_loading_leaf weui_loading_leaf_7"></div>
            <div class="weui_loading_leaf weui_loading_leaf_8"></div>
            <div class="weui_loading_leaf weui_loading_leaf_9"></div>
            <div class="weui_loading_leaf weui_loading_leaf_10"></div>
            <div class="weui_loading_leaf weui_loading_leaf_11"></div>
        </div>
        <p class="weui_toast_content">数据加载中</p>
    </div>
</div>
</body>
<?php if (!$error) { ?>
<script>
        var lin = true;
        function lingqu(){
            if(!lin) return;
            $('#loadingToast').show();
            lin = false;
            $.getJSON('/red/linhongbao',{token:'<?php echo $_GET['token']?>',vid:'<?php echo $_GET['vid']?>'},function(ret){
                $('#loadingToast').hide();
                if(ret.error == 0){
                    myAlert('成功',ret.error_reason);
                }else{
                    myAlert('失败',ret.error_reason);
                }
                lin = true;
            });
        }

        function myAlert(title,msg){
            var html = '<div id="weui_dialog_alert" class="weui_dialog_alert">' +
                            '<div class="weui_mask">' +
                        '</div>' +
                        '<div class="weui_dialog">' +
                            '<div class="weui_dialog_hd">' +
                            '<strong class="weui_dialog_title">' +
                            title+
                            '</strong>' +
                            '</div>' +
                            '<div class="weui_dialog_bd">' +
                            msg +
                            ' </div>' +
                            '<div class="weui_dialog_ft">' +
                            '<a onclick="closeAlert()" class="weui_btn_dialog primary">确定</a>' +
                            '</div>' +
                            '</div>' +
                        '</div>';
            $('body').append(html);
        }

    function closeAlert(){
        $('#weui_dialog_alert').remove();
    }


</script>
<?php } ?>

</html>