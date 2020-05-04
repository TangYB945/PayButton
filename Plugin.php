<?php
/**
 * 
 * 添加打赏按钮 
 * @package PayButton
 * @author Tonm
 * @version 1.0.1
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
      	echo ('<style>.typecho-page-main a{background-color:#4F94CD;color:#FFFFFF;padding:2px 6px;border-radius:3px;line-height:15px;display:inline-block}.typecho-page-main a:hover{color:red}</style>');
		$version = '1.0.1'; 
		$up = json_decode(@file_get_contents('https://owo-bo.cn/effects/PayButton/owo.php'), true);
      	$arr = json_decode(@file_get_contents(''.$up['url'].''.$up['bo'].''), true);
		if(empty($arr['tag_name'])){
			$new_version = '获取失败！';
		}else{
			$new_version = $arr['tag_name'];
		}
      	if($new_version === '获取失败！'){
			$version_tips = '获取失败！请自行前往<a href="'.$arr['browser_download_url'].'" target="_blank">OwO-Bo.CN</a>获取详情！';
			$new_version_out = '<font color="#ee9922">获取失败！</font>';
		}elseif($version < $new_version) {
        	$version_tips = '该插件有<font color="#ee9922">新版本</font> => <a href="'.$arr['browser_download_url'].'" target="_blank">点击下载</a>';
			$new_version_out = '<font color="#ee9922">'.$new_version.'</font>';
		}elseif($version > $new_version){
            $version_tips = '版本比官方还高啦！请检查版本信息呀！';
          	$new_version_out = '<font color="#ee9922">'.$new_version.'</font>';
        }elseif($version = $new_version){
			$version_tips = '您的插件为<font color="#ee9922">最新版本</font>，无需更新！';
          	$new_version_out = $new_version;
		}
        $public_section = new Typecho_Widget_Helper_Layout('div', array('class=' => 'typecho-page-title'));
        $public_section->html('<h4>本插件目前版本：'.$version.' | 最新版本：'.$new_version_out.'（'.$version_tips.'）</h4>');
        $form->addItem($public_section);
        $jquery = new Typecho_Widget_Helper_Form_Element_Radio('jquery',
            ['0' => _t('不加载'), '1' => _t('加载')],
            '0', _t('是否加载外部jQuery库'), _t('插件需要jQuery库文件的支持，如果已加载就不需要加载辽'));
        $form->addInput($jquery);
        $mdui = new Typecho_Widget_Helper_Form_Element_Radio('mdui',
            ['0' => _t('不加载'), '1' => _t('加载')],
            '0', _t('是否加载外部mdui库'), _t('由于个人比较懒，就不想去密密麻麻的mdui里提取相应样式了，直接引用了官方文件<br>如果主题已加载就不需要加载了'));
        $form->addInput($mdui);
        $theme = new Typecho_Widget_Helper_Form_Element_Text('theme', null, '#3498db', _t('按钮颜色'), '这里填写十六进制颜色代码哦');
        $form->addInput($theme);
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    public static function render(){}

    public static function button(){
    	$theme = Typecho_Widget::widget('Widget_Options') -> Plugin('PayButton') -> theme;
    	echo '<!-- 感谢使用本插件 -->';
        echo '
        <div class="article-btn">
			<button style="background-color:'.$theme.'" onclick="datonmToggle();return false;" class="article-pay-btn mdui-btn mdui-btn-raised mdui-btn-dense mdui-color-theme-accent" title="打赏，支持一下"><i class="mdui-icon material-icons">monetization_on</i>打 赏</button>
	
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
        $mdui = Helper::options()->plugin('PayButton')->mdui;
        if($mdui) {
            echo '<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/mdui/0.4.3/css/mdui.min.css">';
        }
    }

    public static function footer()
    {
        $jsUrl = Helper::options()->pluginUrl . '/PayButton/static/pay.js';
        printf("<script type='text/javascript' src='%s'></script>\n", $jsUrl);
	}
}
