
{% include "layout/header.volt" %}

<section class="rt_wrap content mCustomScrollbar">
    <div class="rt_content">
        <div class="page_title">
            <h2 class="fl">{{vinfo.name}}活动地图数据</h2>
        </div>
        <div style="width: 100%;height: 800px;" id="container">

        </div>
    </div>

</section>

<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>

<script>

    var list = {{list}};
    console.dir(list);
    function init() {
        var map = new qq.maps.Map(document.getElementById("container"), {
            center: new qq.maps.LatLng({{vinfo.lt}}),
            zoom: 15
        });

        //创建一个Marker
        var marker = new qq.maps.Marker({
            //设置Marker的位置坐标
            position: new qq.maps.LatLng({{vinfo.lt}}),
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


{% include "layout/footer.volt" %}


