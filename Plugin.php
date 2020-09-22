<?php
/**
 * 
 * 添加打赏按钮 
 * @package PayButton
 * @author Tonm
 * @version 1.0.5
 * @link https://owo-bo.cn/
 * 
 */
$owo = json_decode(@file_get_contents(Helper::options()->pluginUrl . '/PayButton/static/owo.json'), true);
define("TONM_NAME", $owo['name']);define("TONM_VERSION", $owo['version']);define("TONM_URL", $owo['url']);define("TONM_STATIC", $owo['static']);define("TONM_REWARD", $owo['reward']);
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
      	echo ('<style>.typecho-page-main a{background-color:#4F94CD;color:#FFFFFF;padding:2px 6px;border-radius:3px;line-height:15px;display:inline-block}.typecho-page-main a:hover{color:red}</style>');
		$obtain=Helper::options()->pluginUrl . '/PayButton/static/obtain.min.js';
		echo '<script type="text/javascript" src="//cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>';
		echo '<script type="text/javascript" src="'.$obtain.'"></script>';
		echo '<div id="data" data-update="'.base64_encode('theme='.TONM_NAME.'&version='.TONM_VERSION).'"></div>';
		$new_verison= '<span id="verison"></span>';
		$new_notice= '<span id="notice"></span>';
        $public_section = new Typecho_Widget_Helper_Layout('div', array('class=' => 'typecho-page-title'));
        $public_section->html('<h4>本插件目前版本：'.TONM_VERSION.' | 最新版本：'. $new_verison.'（'.$new_notice.'）</h4>');
        $form->addItem($public_section);
        $jquery = new Typecho_Widget_Helper_Form_Element_Radio('jquery',
            ['0' => _t('不加载'), '1' => _t('加载')],
            '0', _t('是否加载外部jQuery库'), _t('插件需要jQuery库文件的支持，如果已加载就不需要加载辽'));
        $form->addInput($jquery);
        $cdnurl=new Typecho_Widget_Helper_Form_Element_Radio('cdnurl',
            ['0' => _t('jsdelivr'), '1' => _t('local')],
            '0', _t('静态文件源'), _t('默认为 “JsDelivr源”'));
    	$form->addInput($cdnurl);
        $felurl = new Typecho_Widget_Helper_Form_Element_Text('felurl', null, 'https://owo-bo.cn/pic/MainImg/PayButton/', _t('二维码外链地址'), '请填写二维码的外链地址呀！（eg：填到当前文件所在目录即可，链接末尾一定不要忘记反斜杠“/”呀）');
        $form->addInput($felurl);
        $alifelname = new Typecho_Widget_Helper_Form_Element_Text('alifelname', null, 'ali.png', _t('支付宝二维码名称'), '请填写支付宝二维码图片的名称呀！（eg：支付宝二维码图片名称是ali.png，直接填上即可）');
        $form->addInput($alifelname);
        $weifelname = new Typecho_Widget_Helper_Form_Element_Text('weifelname', null, 'wei.png', _t('微信二维码名称'), '请填写微信二维码图片的名称呀！（eg：微信二维码图片名称是wei.png，直接填上即可）');
        $form->addInput($weifelname);
        $pendant = new Typecho_Widget_Helper_Form_Element_Radio('pendant',
            ['0' => _t('关闭'), '1' => _t('开启')],
            '0', _t('2233娘'), _t('2233娘呼唤你啦！'));
        $form->addInput($pendant);
        $Sakura = new Typecho_Widget_Helper_Form_Element_Radio('Sakura',
            ['0' => _t('关闭'), '1' => _t('开启')],
            '0', _t('樱花飘呀飘'), _t('开启满屏樱花飘，不需要可以关闭呐！'));
        $form->addInput($Sakura);
        $moreClor = new Typecho_Widget_Helper_Form_Element_Radio('moreClor',
            ['0' => _t('关闭'), '1' => _t('开启')],
            '0', _t('多彩文字'), _t('是否开启多彩一言？？？OwO'));
        $form->addInput($moreClor);
        $onetxt = new Typecho_Widget_Helper_Form_Element_Text('onetxt', null, '感谢您的支持，我会继续努力哒!', _t('自定义一言'), '一言尽量限制在18个字符左右，不然就会发生奇奇怪怪的事');
        $form->addInput($onetxt);
        $tClor = new Typecho_Widget_Helper_Form_Element_Radio('tClor',
            ['0' => _t('白色'), '1' => _t('黑色')],
            '0', _t('按钮文本颜色'), _t('只提供了暗色和亮色，防止设置按钮背景色时看不清字体'));
        $form->addInput($tClor);
        $theme = new Typecho_Widget_Helper_Form_Element_Text('theme', null, '#3498db', _t('按钮颜色'), '如果按钮没有颜色，请在这里填写十六进制颜色代码哦');
        $form->addInput($theme);
    }
    
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    public static function render(){}

    public static function button(){
    	$owo = json_decode(@file_get_contents(Helper::options()->pluginUrl . '/PayButton/static/owo.json'), true);
    	$theme = Typecho_Widget::widget('Widget_Options') -> Plugin('PayButton') -> theme;
    	$felurl = Typecho_Widget::widget('Widget_Options') -> Plugin('PayButton') -> felurl;
    	$alifelname = Typecho_Widget::widget('Widget_Options') -> Plugin('PayButton') -> alifelname;
    	$weifelname = Typecho_Widget::widget('Widget_Options') -> Plugin('PayButton') -> weifelname;
    	$onetxt = Typecho_Widget::widget('Widget_Options') -> Plugin('PayButton') -> onetxt;
		$alisplionefelname = substr($alifelname,0,strrpos($alifelname ,"."));
    	$alisplitowfelname = substr($alifelname,strripos($alifelname,".")+1);
		$weisplionefelname = substr($weifelname,0,strrpos($weifelname ,"."));
    	$weisplitowfelname = substr($weifelname,strripos($weifelname,".")+1);
    	$tClor = Typecho_Widget::widget('Widget_Options')->plugin('PayButton')->tClor;
        if($tClor!="0") {
        	$tClor='tonm-btn-dim';
        	$dlClo='icon_dim.png';
        }else {
        	$tClor='tonm-btn-light';
        	$dlClo='icon_light.png';
        }
        $moreClor = Typecho_Widget::widget('Widget_Options')->plugin('PayButton')->moreClor;
        if($moreClor!='0') {
        	$moreClor='class="link"';
        }else{
        	$moreClor='';
        }
        $pendant = Typecho_Widget::widget('Widget_Options')->plugin('PayButton')->pendant;
        if ($pendant!="0") {
        	$pendant=$owo['2233'];
        }else{
        	$pendant='';
        }
        $Sakura = Typecho_Widget::widget('Widget_Options')->plugin('PayButton')->Sakura;
        if($Sakura!='0') {
        	$Sakura_s='start();';
        	$Sakura_e='end();';
        }else{
        	$Sakura_s='';
        	$Sakura_e='';
        }
        $cdnurl= Typecho_Widget::widget('Widget_Options')->plugin('PayButton')->cdnurl;
        if ($cdnurl!="0") {
        	$cdnurl_r = Helper::options()->pluginUrl.'/PayButton/reward/';
        	$cdnurl_s = Helper::options()->pluginUrl.'/PayButton/static/';
        }else{
    		$cdnurl_r = TONM_URL.TONM_VERSION.TONM_REWARD;
        	$cdnurl_s = TONM_URL.TONM_VERSION.TONM_STATIC;
        }
    	echo '<!-- 感谢使用本插件 -->';
        echo '
			<button style="background-color:'.$theme.'" onclick="datonmToggle();'.$Sakura_s.'return false;" class="article-pay-btn tonm-btn tonm-btn-raised tonm-btn-dense '.$tClor.'" title="打赏，支持一下">
				<img class="banimg" src="'.$cdnurl_r.''.$dlClo.'">
				<span>打 赏</span>
			</button>
			<div class="hide_box"></div>
			<div class="tonm_box">
				<a class="tonm_close" href="#" onclick="datonmToggle();'.$Sakura_e.'return false;" title="关闭"><img class="banimg" src="'.$cdnurl_r.'close.jpg" alt="取消" /></a>
				<div class="tonm_tit">
					<p '.$moreClor.'>'.$onetxt.'</p>
				</div>
				<div class="tonm_payimg" style="border: 6px solid '.$theme.'">
					<img class="banimg" src="'.$felurl.''.$alifelname.'" />
				</div>
					<div class="pay_explain">'.$pendant.'扫码打赏<br>支付金额随意哦！</div>
				<div class="tonm_payselect">
					<div class="pay_item checked" data-id="'.$alisplionefelname.'">
			    		<span class="radiobox"></span>
			    		<span class="pay_logo"><img class="banimg" src="'.$cdnurl_r.'alipay.jpg" alt="支付宝" /></span>
					</div>
					<div class="pay_item" data-id="'.$weisplionefelname.'">
			    		<span class="radiobox"></span>
			    		<span class="pay_logo"><img class="banimg" src="'.$cdnurl_r.'wechat.jpg" alt="微信" /></span>
					</div>
				</div>
				<div class="tonm_info">
					<p '.$moreClor.'>打开<span id="tonm_pay_txt">支付宝</span>扫一扫，即可进行扫码打赏哦</p>
				</div>
			</div>
		';
        echo '
        <script type="text/javascript">
			$(function(){
				$(".pay_item").click(function(){
					$(this).addClass("checked").siblings(".pay_item").removeClass("checked");
					var dataid=$(this).attr("data-id");
					if ("'.$weisplitowfelname.'"=="'.$alisplitowfelname.'") {
						$(".tonm_payimg img").attr("src","'.$felurl.'"+dataid+".png");
					}else{
						alert("呀！粗问题啦！请到后台检查两张图片扩展名是否一致！");
					}
					$("#tonm_pay_txt").text(dataid=="'.$alisplionefelname.'"?"支付宝":"微信");
				});
			});
			$(document).on("pjax:complete",function(){banimg();clatest();colorize();});
		</script>';
    }
    
    public static function header()
    {
    	$cdnurl= Typecho_Widget::widget('Widget_Options')->plugin('PayButton')->cdnurl;
        if ($cdnurl!="0") {
        	$cdnurl_r = Helper::options()->pluginUrl.'/PayButton/static/';
        }else{
        	$cdnurl_r = TONM_URL.TONM_VERSION.TONM_STATIC;
        }
        echo '<link rel="stylesheet" href="'.$cdnurl_r.'paybtn.min.css">';
        $Sakura= Typecho_Widget::widget('Widget_Options')->plugin('PayButton')->Sakura;
        if ($Sakura) {
        	 echo '<script type="text/javascript" src="'.$cdnurl_r.'sakura.min.js"></script>';
        }
        $jquery = Helper::options()->plugin('PayButton')->jquery;
        if($jquery) {
            echo '<script type="text/javascript" src="//cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>';
        }
    }
    
    public static function footer()
    {
    	$cdnurl= Typecho_Widget::widget('Widget_Options')->plugin('PayButton')->cdnurl;
        if ($cdnurl!="0") {
        	$cdnurl_r = Helper::options()->pluginUrl.'/PayButton/static/';
        }else{
        	$cdnurl_r = TONM_URL.TONM_VERSION.TONM_STATIC;
        }
        echo '<script type="text/javascript" src="'.$cdnurl_r.'pay.min.js"></script>';
	}
}
