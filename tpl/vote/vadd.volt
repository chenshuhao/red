{% include "layout/header.volt" %}
<script src='/laydate/laydate.js' ></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<section class="rt_wrap content mCustomScrollbar">
  <div class="rt_content">
  <form method='post' enctype="multipart/form-data">
    <section>
      <h2><strong style="color:grey;">添加现金红包</strong></h2>
      {% if voteinfo.id %}
        <input type="hidden" class="textbox textbox_295" name="id" value="{{voteinfo.id}}"/>
      {% endif%}

      <ul class="ulColumn2">
        <li>
          <span class="item_name" style="width:120px;">活动名称：</span>
          <input type="text" class="textbox textbox_295" name="name" placeholder="活动名称,10个字符" value="{{voteinfo.name}}"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">领红包关键词：</span>
          <input type="text" class="textbox textbox_295" placeholder="红包关键词" name="key" value="{{voteinfo.key}}"/>
            <span class="errorTips">(请勿在其他地方重复使用，否则将无法发送红包。)</span>
        </li>
        <li>
          <span class="item_name" style="width:120px;">活动开始时间：</span>
          <input type="text" class="textbox textbox_295" placeholder="请选择开始时间" name='stime'  class="laydate-icon" value="{{voteinfo.stime}}" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">活动结束时间：</span>
          <input type="text" class="textbox textbox_295" placeholder="请选择开始时间" name='etime'  class="laydate-icon" value="{{voteinfo.etime}}" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">活动预算金额：</span>
          <input type="text" class="textbox textbox_295" placeholder="" name='money' value="{{voteinfo.money}}" />
        </li>
          <li>
          <span class="item_name" style="width:120px;">每人可领取次数：</span>
          <input type="text" class="textbox textbox_295" placeholder="" name='num' value="{{voteinfo.num}}" />
        </li>
          <li>
          <span class="item_name" style="width:120px;">禁止领取时间段：</span>
          <input type="text" class="textbox textbox_295" placeholder="禁止领取时间开始  0-23 时" name='sd'  value="{{voteinfo.sd}}"/>
          -
          <input type="text" class="textbox textbox_295" placeholder="禁止领取时间结束  0-23 时" name='ed' value="{{voteinfo.ed}}"/>
          <span class="errorTips">((该时间段内不能领取红包,默认0-0时,全天可领))</span>
        </li>
          <li>
          <span class="item_name" style="width:120px;">红包金额起始值：</span>
          <input type="text" class="textbox textbox_295" placeholder="不得小于1元" name='smoeny'  value="{{voteinfo.smoeny}}"/>
          -
          <input type="text" class="textbox textbox_295" placeholder="不得大于200元" name='emoney' value="{{voteinfo.emoney}}"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">企业名称：</span>
          <input type="text" class="textbox textbox_295" placeholder="8个字以内" name='qname' value="{{voteinfo.qname}}"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">红包祝福语：</span>
          <input type="text" class="textbox textbox_295" placeholder="20个字以内" name='wsing' value="{{voteinfo.wsing}}"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">可领红包范围：</span>
          <input type="text" class="textbox textbox_295" placeholder="公里" name='fanwei' value="{{voteinfo.fanwei}}">
          <span class="errorTips">(为0 则不限制范围)</span>
        </li>
        <li>
          <span class="item_name" style="width:120px;">中心点选择：</span>
            <input type="text" class="textbox textbox_295"  id="dizhi"> <a onclick="codeAddress()" class="link_btn" >定位</a>
            <div id="container" style='height:300px;width:500px;    margin-left: 128px;'></div>
            <div style="height:22px;"></div>
            <div id="fitBoundsDiv"></div>
            <div id="centerDiv" style="margin-left:128px;"></div>
            <div id="zoomDiv"></div>
            <div id="containerDiv"></div>
            <div id="mapTypeIdDiv"></div>
            <div id="projection"></div>
              <input type="hidden" class="textbox textbox_295" id='lt' name='lt' value="{{voteinfo.lt}}">

        </li>

        <li>
          <span class="item_name" style="width:120px;">红包图片：</span>
          <label class="uploadImg">
            <input type="file" name='tup'/>
            <span>上传图片</span>
          </label>

          <img src="/{{voteinfo.tup?voteinfo.tup:'images/default/hb_1.jpg'}}" width="300px">

          <span class="errorTips">(若您是订阅号,请直接上传带二维码的推广图片,推广图最佳尺寸640x1006)</span>
        </li>
        <li>
          <span class="item_name" style="width:120px;"></span>
          <input type="submit" class="link_btn"/>
        </li>
      </ul>
      </section>
  </form>
  </div>
</section>
  <script type="text/javascript">
    function init() {

      //div容器
      var container = document.getElementById("container");
      var centerDiv = document.getElementById("centerDiv");


      {% if voteinfo.lt %}
      //初始化地图
      var map = new qq.maps.Map(container, {
        // 地图的中心地理坐标
        center: new qq.maps.LatLng({{voteinfo.lt}}),
        zoom: 13
      });
      {% else %}
      //初始化地图
      var map = new qq.maps.Map(container, {
        // 地图的中心地理坐标
        center: new qq.maps.LatLng(39.916527, 116.397128),
        zoom: 13
      });

      //获取城市列表接口设置中心点
      var citylocation = new qq.maps.CityService({
        complete : function(result){
          map.setCenter(result.detail.latLng);
        }
      });
      //根据用户IP查询城市信息。
      citylocation.searchLocalCity();

      {% endif %}


      //调用地址解析类
      geocoder = new qq.maps.Geocoder({
        complete : function(result){
          map.setCenter(result.detail.location);
          $('#lt').val(map.getCenter());
        }
      });

        var middleControl = document.createElement("div");
        middleControl.style.left="232px";
        middleControl.style.top="132px";
        middleControl.style.position="relative";
        middleControl.style.width="36px";
        middleControl.style.height="36px";
        middleControl.style.zIndex="100000";
        middleControl.innerHTML ='<img src="https://www.cdlhome.com.sg/mobile_assets/images/icon-location.png" />';
        document.getElementById("container").appendChild(middleControl);
      //返回地图当前中心点地理坐标
      centerDiv.innerHTML = "latlng:" + map.getCenter();
      //当地图中心属性更改时触发事件
      qq.maps.event.addListener(map, 'center_changed', function() {
        centerDiv.innerHTML = "latlng:" + map.getCenter();
        $('#lt').val(map.getCenter());
      });
      }

      init();

      function codeAddress() {
        var address = document.getElementById("dizhi").value;
        //通过getLocation();方法获取位置信息值
        geocoder.getLocation(address);
      }
    </script>

{% include "layout/footer.volt" %}
