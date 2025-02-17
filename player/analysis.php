<?php
header("Cache-Control: no-cache, must-revalidate");//清理浏览器缓存
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');//禁止页面被缓存
error_reporting(0);//抑制所有错误信息
include "tj.php";
require $_SERVER["DOCUMENT_ROOT"] . "/admin/data.php";
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: x-requested-with,content-type");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
header("Connection: keep-alive");
header("Transfer-Encoding: chunked");
if ($yzm["fdhost_on"] == "on") {
	$urlArr = explode("//", $_SERVER["HTTP_REFERER"])[1];
	$host = explode("/", $urlArr)[0];
	$host = explode(":", $host)[0];
	$fdhost = explode(",", $yzm["fdhost"]);
	$localhost = explode(":", $_SERVER["HTTP_HOST"])[0];
	$fdhost[] = $localhost;
	if ($yzm["blank_referer"] == "on") {
		$fdhost[] = "";
	}
	if (!in_array($host, $fdhost)) {
		exit("<html><meta name=\"robots\" content=\"noarchive\">
                    	  <style>h1{color:#FFFFFF; text-align:center; font-family: Microsoft Jhenghei;}p{color:#CCCCCC; font-size: 1.2rem;text-align:center;font-family: Microsoft Jhenghei;}</style>
                    	  <body bgcolor=\"#000000\"><table width=\"100%\" height=\"100%\" align=\"center\"><td align=\"center\"><h1>" . $yzm["referer_wenzi"] . "</font><font size=\"2\"></font></p></table><script src=\"https://cdn.jsdelivr.net/gh/Zrahh/JsDelivr_CDN/assets/js/jquery.min.js\"></script><script>\$(\"#my-loading\", parent.document).remove();</script></body>
                  </html>");
	}
}
$url = preg_replace("/v=/", "", $_SERVER["QUERY_STRING"], 1);
$url = explode("&next=", $url)[0];
if (strpos($url, "www.bilibili.com") && wp_is_mobile() || strpos($url, "m.bilibili.com")) {
	$url = str_replace("m.bilibili.com", "www.bilibili.com", $url);
	$url = str_replace("www.bilibili.com", "m_www.bilibili.com", $url);
}
if (strpos($url, "www.mgtv.com") && wp_is_mobile()) {
	$url = str_replace("www.mgtv.com", "m_www.mgtv.com", $url);
}
$preg = "/^http(s)?:\\/\\/.+/";
$type = "";
if (preg_match($preg, $url)) {
	if (strstr($url, ".mp4") == true || strstr($url, ".php") == true || strstr($url, ".flv") == true) {
		$type = explode("&next=", $url)[0];
		$metareferer = "never";
	}
}
if ($type == "") {
	$fh = get_url("" . $yzm["fufeng"] . "/api/?key=". "&key=" . $yzm["key"] . "&url=" . $url);
	$jx = json_decode($fh, true);
	$type = ($jx["url"]);
	$metareferer = $jx["metareferer"];
	if ($metareferer == "") {
		$metareferer = "never";
	}
}

if ($type == "") {
	$fh = get_url($yzm["api1"] . $url);
	$jx = json_decode($fh, true);
	$type = $jx["url"];
	$metareferer = $jx["metareferer"];
	if ($metareferer == "") {
		$metareferer = "never";
	}
}
if ($type == "") {
	$fh = get_url($yzm["api2"] . $url);
	$jx = json_decode($fh, true);
	$type = $jx["url"];
	$metareferer = $jx["metareferer"];
	if ($metareferer == "") {
		$metareferer = "never";
	}
}
if ($type == "") {
	$fh = get_url($yzm["api3"] . $url);
	$jx = json_decode($fh, true);
	$type = $jx["url"];
	$metareferer = $jx["metareferer"];
	if ($metareferer == "") {
		$metareferer = "never";
	}
}
if ($type == "") {
	$fh = get_url($yzm["api4"] . $url);
	$jx = json_decode($fh, true);
	$type = $jx["url"];
	$metareferer = $jx["metareferer"];
	if ($metareferer == "") {
		$metareferer = "never";
	}
}
if ($type == "") {
	$fh = get_url($yzm["api5"] . $url);
	$jx = json_decode($fh, true);
	$type = $jx["url"];
	$metareferer = $jx["metareferer"];
	if ($metareferer == "") {
		$metareferer = "never";
	}
}
if ($type == "") {
	exit("<html><meta name=\"robots\" content=\"noarchive\">
                	  <style>h1{color:#FFFFFF; text-align:center; font-family: Microsoft Jhenghei;}p{color:#CCCCCC; font-size: 1.2rem;text-align:center;font-family: Microsoft Jhenghei;}</style>
                	  <body bgcolor=\"#000000\"><table width=\"100%\" height=\"100%\" align=\"center\"><td align=\"center\"><h1>" . $yzm["error_wenzi"] . "</font><font size=\"2\"></font></p></table><script src=\"/mizhiplayerapi/js/jquery.min.js\"></script><script>\$(\"#my-loading\", parent.document).remove();</script></body>
              </html>");
}

if ($yzm["khd"] == "dp") {
	if (!wp_is_mobile()) {
		$file = "./html/dplayer.htm";
		include $file;
		exit;
	} else {
		include "./html/h5player.htm";
		exit;
	}
}
if ($yzm["khd"] == "h5") {
	if (!wp_is_mobile()) {

	} else {
		include "./html/h5player.htm";
		exit;
	}
}
function jie($str){
    //解密
    $txt=$str;
    $pass="202205051426239465";//加密解密 密码
    $th="觅知博客提供请勿盗用,https://www.98dou.cn";//KEY可以填写相关广告语
    $AA=pz_zwbth($txt,"----".$th."----","6");
    $DATA=pz_RC4($AA,$pass,1);
    return $DATA;
}

function pz_RC4($data,$pwd,$t) {//此方式支持e4a和易语言，t=0加密，1=解密
    if($t > 1){
        return $data;
    }
         else{
        $cipher = '';
        $key[] = "";
        $box[] = "";
        $pwd_length = strlen($pwd);
        if($t == 1){
            $data = hex2bin($data);
        }
        $data_length = strlen($data);
        for ($i = 0; $i < 404; $i++) {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 404; $i++) {
            $j = ($j + $box[$i] + $key[$i]) % 404;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $data_length; $i++) {
            $a = ($a + 1) % 404;
            $j = ($j + $box[$a]) % 404;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $k = $box[(($box[$a] + $box[$j]) % 404)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }
        if($t == 1){
            return $cipher;
        }else{
            return bin2hex($cipher);
        }
    }

}
function pz_zwbth($txt,$a,$b){
    $data=str_replace($a,$b,$txt);

    return $data;
}
?>

<!DOCTYPE html>
<html>
<head>

<!--//不缓存代码-->
<meta HTTP-EQUIV="pragma" CONTENT="no-cache"> 
<meta HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate"> 
<meta HTTP-EQUIV="expires" CONTENT="0">
<!--//不缓存代码-->
<!--这里开始-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximum-scale=1,minimum-scale=1,viewport-fit=cover">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><!-- IE内核 强制使用最新的引擎渲染网页 -->
<meta name="renderer" content="webkit">  <!-- 启用360浏览器的极速模式(webkit) -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="x5-fullscreen" content="true"/>
<meta name="x5-page-mode" content="app"/> <!-- X 全屏处理 -->
<meta name="full-screen" content="yes" />
<meta name="browsermode" content="application" />  <!-- UC 全屏应用模式 -->
<meta name="apple-mobile-web-app-capable" content="yes "/> <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" /> <!--  苹果全屏应用模式 --> 

<meta name="theme-color" content="#de698c">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="Cache-Control" content="no-transform">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<meta name="applicable-device" content="mobile">
<meta name="screen-orientation" content="portrait">
<meta name="x5-orientation" content="portrait">

<!--这里结束-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta charset="UTF-8">
<meta name="referrer" content="<?php echo $metareferer;?>">
<?php if($yzm['isDebuggerOk']=="on"){ ?>
<script src="/mizhiplayerapi/js/fuck-debugger.js"></script>
	<?php } ?>  
  <?php 

$logo_width_height = explode(",", $yzm["logo_width_height"]);
?>

<link rel="stylesheet" href="/mizhiplayerapi/css/yzmplayer.css">
<style>
    .yzmplayer-setting-speeds:hover .title, .yzmplayer .yzmplayer-controller .yzmplayer-icons.yzmplayer-comment-box .yzm-yzmplayer-send-icon {
    	background-color: <?php echo $yzm["color"];?> !important;
    }
    .showdan-setting .yzmplayer-toggle input+label, .yzmplayer-volume-bar-inner, .yzmplayer-thumb, .yzmplayer-played, .yzmplayer-comment-setting-box .yzmplayer-setting-danmaku .yzmplayer-danmaku-bar-wrap .yzmplayer-danmaku-bar .yzmplayer-danmaku-bar-inner, .yzmplayer-controller .yzmplayer-icons .yzmplayer-toggle input+label, .yzmplayer-controller .yzmplayer-icons.yzmplayer-comment-box .yzmplayer-comment-setting-box .yzmplayer-comment-setting-type input:checked+span, .yzmplayer-controller .yzmplayer-icons.yzmplayer-comment-box .yzmplayer-comment-setting-box .yzmplayer-comment-setting-font input:checked+span  {
        background: <?php echo $yzm["color"];?> !important;
    }
    .yzmplayer-logo {
        width: <?php echo $logo_width_height[0];?>px !important;
        height: <?php echo $logo_width_height[1];?>px !important;
    }
</style>
<script src="/mizhiplayerapi/js/jquery.min.js"></script>
<script src="/mizhiplayerapi/js/setting.js"></script>
<script src="/mizhiplayerapi/js/yzmplayer.js"></script>
	<?php 
if (!strpos($type, ".flv")) {
	if ($yzm["p2p"] == "on") {
		?><script type="text/javascript" src="/mizhiplayerapi/js/hls.p2p.js"></script><?php 
	} else {
		?><script type="text/javascript" src="/mizhiplayerapi/js/hls.min.js"></script><?php 
	}
} else {
	?><script type="text/javascript" src="/mizhiplayerapi/js/flv.min.js"></script><?php 
}
if (!($yzm["public_dmku"] == "on")) {
    $dmku = "/dmku/";
} else {
    $dmku = "$yzm[gg_dmku]";
}
?>

<script src="/mizhiplayerapi/js/layer.js"></script>
</head>
<body>
<div id="player"></div>
<div id="ADplayer"></div>
<div id="ADtip"></div>
<script>
    

    var up = {
        "usernum": "<?php echo $users_online;?>",
        "mylink": "",
        "diyid": [0, "游客", 1]
    }
    
    var config = {
        "api": "<?php echo $dmku;?>",
        "av": "<?php echo $yzm["av_dmku"];?>",
        "url": "<?php echo pz_jh4(($type),'202205051426239465',0);?>",
    	"id":"<?php echo substr(md5($_GET["v"]), -20);?>",
    	"sid":"<?php echo $_GET["sid"];?>",
    	"pic":"<?php echo $_GET["pic"];?>",
    	"title":"<?php echo $_GET["name"];?>",
    	"next":"<?php echo $_GET["next"];?>",
    	"user": "<?php echo $_GET["user"];?>",
    	"group": "<?php echo $_GET["group"];?>",
    }
    config.contextmenu = [{text:"<?php echo $yzm["right_wenzi"];?>",link:"<?php echo $yzm["right_link"];?>"}];
    
    YKQ.start();
    
    var _clearTimer = window.setInterval(function(){

        var _rightWenzi = "<?php echo $yzm["right_wenzi"];?>";
        var _rightLink  = "<?php echo $yzm["right_link"];?>";
        var _menuItemDom= $(".yzmplayer-menu .yzmplayer-menu-item").eq(1);
        
        if(_menuItemDom.length > 0 && _menuItemDom.html().length > 0){
            $("#my-loading", parent.document).remove();
            window.clearInterval(_clearTimer);
            _menuItemDom.find("a").attr("href",_rightLink);
            _menuItemDom.find("a").html(_rightWenzi);
            
        }
        
    });
    
</script>
<script>
function adCheck(){
 var myDate = new Date();
 var aaa=myDate.getHours();
 if(parseInt(aaa)>=1 && parseInt(aaa)<=5 ){  //投放时间设置（默认是凌晨1点到早上5点，根据自己的需求自己修改）
   return true;
 }else{
   return false;
 }
}
 if(adCheck()){
document.writeln('<script type="text/javascript" charset="UTF-8" async src="<?php echo $yzm["footer"];?>"><\/script>');//此处广告联盟js是放你广告联盟获取的js链接
 }
 </script>  

</body></html><?php 
function get_url($url)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);// 设置网址
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);	// 过SSL验证证书1
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);	// 过SSL验证证书2
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // 设置重定向
	curl_setopt($curl, CURLOPT_HEADER, false);// 将头部作为数据流输出
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);// 设置以变量形式存储返回数据
	$return = curl_exec($curl);// 请求并存储数据
	curl_close($curl);	// 关闭cURL
	return $return;// 返回数据
}
function wp_is_mobile()
{
	static $is_mobile;
	if (isset($is_mobile)) {
		return $is_mobile;
	}
	if (empty($_SERVER["HTTP_USER_AGENT"])) {
		$is_mobile = false;
	} else {
		if (strpos($_SERVER["HTTP_USER_AGENT"], "Mobile") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Android") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Silk/") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Kindle") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "BlackBerry") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Opera Mini") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Opera Mobi") !== false) {
			$is_mobile = true;
		} else {
			$is_mobile = false;
		}
	}
	return $is_mobile;
}
function pz_jh4($data,$pwd,$t) {//此方式支持e4a和易语言，t=0加密，1=解密
    if($t > 1){
        return $data;
    }
    else{
        $cipher = '';
        $key[] = "";
        $box[] = "";
        $pwd_length = strlen($pwd);
        if($t == 1){
            $data = base64_decode($data);
        }else{
            $data = urlencode($data);
            
        }
        $data_length = strlen($data);
        for ($i = 0; $i < 256; $i++) {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $data_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }
        if($t == 1){
            return $cipher;
        }else{
            return base64_encode($cipher);
        }
    }

}

