<?php
/**
 * 
 * 添加打赏按钮 
 * @package PayButton
 * @author Tonm
 * @version 1.0.0
 * @link https://owo-bo.cn/
 * 
 */
class PayButton_Plugin implements Typecho_Plugin_Interface
{

    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->footer = array('PayButton_Plugin', 'footer');
        Typecho_Plugin::factory('Widget_Archive')->header = array('PayButton_Plugin', 'header');
    	return '启用成功ヾ(≧▽≦*)o，请设置您的相关配置';
    }


    public static function deactivate()
    {
    	return '你终究还是抛弃了我!';
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $jquery = new Typecho_Widget_Helper_Form_Element_Radio('jquery',
            ['0' => _t('不加载'), '1' => _t('加载')],
            '0', _t('是否加载外部jQuery库'), _t('插件需要jQuery库文件的支持，如果已加载就不需要加载了'));
        $form->addInput($jquery);
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    public static function render(){}

    public static function button(){
    	echo '<!-- 感谢使用本插件 -->';
        echo '
        <div class="article-btn">
			<button onclick="datonmToggle();return false;" class="mdui-btn mdui-btn-raised mdui-color-theme-accent article-btn datonm" title="打赏，支持一下">打 赏</button>

			<div class="hide_box"></div>
			<div class="tonm_box">
				<a class="tonm_close" href="#" onclick="datonmToggle();return false;" title="关闭"><img src="'.Helper::options()->pluginUrl.'/PayButton/reward/close.jpg" alt="取消" /></a>
				<div class="tonm_tit">
					<p>感谢您的支持，我会继续努力哒!</p>
				</div>
				<div class="tonm_payimg">
					<img src="'.Helper::options()->pluginUrl.'/PayButton/reward/alipayimg.png" alt="扫码支持" title="扫一扫" />
				</div>
					<div class="pay_explain">扫码打赏<br>支付金额随意哦！</div>
				<div class="tonm_payselect">
					<div class="pay_item checked" data-id="alipay">
			    		<span class="radiobox"></span>
			    		<span class="pay_logo"><img src="'.Helper::options()->pluginUrl.'/PayButton/reward/alipay.jpg" alt="支付宝" /></span>
					</div>
					<div class="pay_item" data-id="weipay">
			    		<span class="radiobox"></span>
			    		<span class="pay_logo"><img src="'.Helper::options()->pluginUrl.'/PayButton/reward/wechat.jpg" alt="微信" /></span>
					</div>
				</div>
				<div class="tonm_info">
					<p>打开<span id="tonm_pay_txt">支付宝</span>扫一扫，即可进行扫码打赏哦</p>
				</div>
			</div>
        </div>';
        echo '
        <script type="text/javascript">
			$(function(){
				$(".pay_item").click(function(){
					$(this).addClass("checked").siblings(".pay_item").removeClass("checked");
					var dataid=$(this).attr("data-id");
					$(".tonm_payimg img").attr("src","'.Helper::options()->pluginUrl.'/PayButton/reward/"+dataid+"img.png");
					$("#tonm_pay_txt").text(dataid=="alipay"?"支付宝":"微信");
				});
			});
			function datonmToggle(){
				$(".hide_box").fadeToggle();
				$(".tonm_box").fadeToggle();
			}
		</script>';
    }

    public static function header()
    {
     	$cssUrl = Helper::options()->pluginUrl . '/PayButton/static/paybtn.min.css';
            echo '<link rel="stylesheet" href="'.$cssUrl.'">';
        $jquery = Helper::options()->plugin('PayButton')->jquery;
        if($jquery) {
            echo '<script type="text/javascript" src="//cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>';
        }
    }
    
    public static function footer()
    {
        $jsUrl = Helper::options()->pluginUrl . '/PayButton/static/pay.js';
        printf("<script type='text/javascript' src='%s'></script>\n", $jsUrl);
	}
}
