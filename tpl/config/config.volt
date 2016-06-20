{% include "layout/header.volt" %}



<section class="rt_wrap content mCustomScrollbar">
  <div class="rt_content">
  <form method='post' enctype="multipart/form-data">
    <section>
      <h2><strong style="color:grey;">支付配置</strong></h2>
      {% if payconfig.id %}
        <input type="hidden" class="textbox textbox_295" name="id" value="{{payconfig.id}}"/>
      {% endif%}


      <ul class="ulColumn2">
      {% if payconfig.token %}
        <li>
          <span class="item_name" style="width:120px;">微信后台接入：</span>
          <input type="text" class="textbox textbox_295"    value="http://www.lexiongmao.cn/weixin?t={{payconfig.token}}"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">TOKEN：</span>
          <input type="text" class="textbox textbox_295"    value="{{payconfig.token}}"/>
        </li>
      {% endif%}
        <li>
          <span class="item_name" style="width:120px;">公众号类型：</span>
          <label class="single_selection"><input type="radio" checked name="type" value="1" <?php if($payconfig->type == 1) echo 'checked'?>/>认证服务号</label>
          <label class="single_selection"><input type="radio" name="type" value="0" <?php if($payconfig->type == 0) echo 'checked'?>/>订阅号,认证订阅好,未认证服务号,未开通微信支付认证服务号</label>
          <p class="single_selection" style="color:red;font-size:16px;font-weight:bold;">
            认证服务号必须填写所有参数<br>
            其他类型公众号 只需要填写 <span style="color:black">公众号原始ID</span>
          </p>
        </li>
        <li>
          <span class="item_name" style="width:120px;">appid：</span>
          <input type="text" class="textbox textbox_295" name="appid"  value="{{payconfig.appid}}"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">secret：</span>
          <input type="text" class="textbox textbox_295" name="secret"  value="{{payconfig.secret}}"/>
        </li>
          <li>
          <span class="item_name" style="width:120px;">微信原始ID：</span>
          <input type="text" class="textbox textbox_295" name="wid"  value="{{payconfig.wid}}"/>
            <span>订阅号必填</span>
        </li>
          <li>
          <span class="item_name" style="width:120px;">商户号：</span>
          <input type="text" class="textbox textbox_295" name="mch_id"  value="{{payconfig.mch_id}}"/>
        </li>
        <li>
          <span class="item_name" style="width:120px;">支付秘钥(key)：</span>
          <input type="text" class="textbox textbox_295" name="pay_key"  value="{{payconfig.pay_key}}"/>
          微信商户后台设置的32位秘钥
        </li>
        <li>
          <span class="item_name" style="width:120px;">证书文件(cret)：</span>
          <input type="file" class="textbox textbox_295" name="cert_file" />
        </li>
          <li>
          <span class="item_name" style="width:120px;">证书文件(key)：</span>
          <input type="file" class="textbox textbox_295" name="key_file" />
        </li>
        <li>
          <span class="item_name" style="width:120px;">证书文件(ca)：</span>
          <input type="file" class="textbox textbox_295" name="ca_file" />
        </li>
        <li>
          <span class="item_name" style="width:120px;"></span>
          <input type="submit" class="link_btn"/>
        </li>
      </ul>
      </section>
  </form>
  </div>



{% include "layout/footer.volt" %}