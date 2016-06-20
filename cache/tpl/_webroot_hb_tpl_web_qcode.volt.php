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
</head>
<body class="msg">
<div class="msg">
    <div class="weui_msg" style="padding-top: 5px;">
        <div class="weui_icon_area" >
            <img src="/<?php echo $tu; ?>" alt="长按扫描二维码" width="98%">
        </div>

        <div class="weui_extra_area">
            <a style="color:white" href="http://apis.map.qq.com/uri/v1/marker?marker=coord:<?php echo $lat; ?>,<?php echo $lng; ?>;title:商家位置;addr:商家位置&referer=myapp">查看商家位置</a>
            <a href="/jszc.html">技术支持:微天下</a>

        </div>
    </div>
</div>
</body>
</html>