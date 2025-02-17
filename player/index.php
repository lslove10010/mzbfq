<?php
header("Cache-Control: no-cache, must-revalidate");//清理浏览器缓存
error_reporting(0);
require $_SERVER["DOCUMENT_ROOT"] . "/admin/data.php";
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: x-requested-with,content-type");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Origin: *");//跨域
header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");//禁止页面被缓存
header("Connection: keep-alive");
header("Transfer-Encoding: chunked");
if ($yzm["fdhost_on"] == "on") {
	$urlArr = explode("//", $_SERVER["HTTP_REFERER"])[1];
	$host = explode("/", $urlArr)[0];
	$host = explode(":", $host)[0];
	$fdhost = explode(",", $yzm["fdhost"]);
	if ($yzm["blank_referer"] == "on") {
		$fdhost[] = "";
	}
	if (!in_array($host, $fdhost)) {
		exit("<html><meta name=\"robots\" content=\"noarchive\">
                    	  <style>h1{color:#FFFFFF; text-align:center; font-family: Microsoft Jhenghei;}p{color:#CCCCCC; font-size: 1.2rem;text-align:center;font-family: Microsoft Jhenghei;}</style>
                    	  <body bgcolor=\"#000000\"><table width=\"100%\" height=\"100%\" align=\"center\"><td align=\"center\"><h1>" . $yzm["referer_wenzi"] . "</font><font size=\"2\"></font></p></table><script src=\"/mizhiplayerapi/js/jquery.min.js\"></script><script>\$(\"#my-loading\", parent.document).remove();</script></body>
                  </html>");
	}
}
	$url = preg_replace("/url=/", "", $_SERVER["QUERY_STRING"], 1);//下一集函数
	$loading_width_height = explode(",", $yzm["loading_width_height"]);
//================设置主页 主题 开始===============
if(!$_GET['url']){
require_once "theme.php";
if($yzm['theme']==1){echo $theme1;}
if($yzm['theme']==2){echo $theme2;}
if($yzm['theme']==3){echo $theme3;}
if($yzm['theme']==4){echo $theme4;}
if($yzm['theme']==5){echo $theme5;}
if($yzm['theme']==6){echo $theme6;}
exit;
}
//================设置主页 主题 结束===============
?>

<!DOCTYPE html><html><head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta charset="UTF-8">
    <title><?php echo $yzm["title"];?></title>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

<style>
    #loading-box {
        background: # !important;
    }
    #my-loading{
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        z-index: 99999;
        background-color:<?php echo $yzm["loading_color"];?>;
	background: url(<?php echo $yzm["loading_bg"];?>) <?php echo $yzm["loading_color"];?>;
	display: block;
    width: 110%;
    height: 110%;
    position: absolute;
    left: -5%;
    top: -5%;
    background-size: cover;
    background-position: 50%;
    -webkit-filter: blur(35px);
    -moz-filter: blur(35px);
    -ms-filter: blur(35px);
    filter: blur(0px);      
    }
    .loading-text{
        position: absolute;
        left: 35px;
        bottom: 7%;
        font-size: 0.5rem;
        color:#999;
    }
    .iframeStyle{
        width: 100%;
        height: 100%;
        border: 0px;
    }
    .loading .pic{
        background: url(<?php echo $yzm["loading_pic"];?>) no-repeat!important;
        background-size: <?php echo $loading_width_height[0];?>px !important;
        width: <?php echo $loading_width_height[0];?>px;
        height: <?php echo $loading_width_height[1];?>px;
        position: absolute;
        pointer-events: none;
        border: 0;
        border-color: unset;
        opacity: 1;
        border-radius: 0;
        -moz-animation: spinoffPulse .9s infinite linear;
        -webkit-animation: unset;
        background-size: 100%;
        top: 50%;
        left: 50%;
        transform: translate3d(-50%,-50%,0px);
    }
    
    body, html {
    font: 24px "Microsoft YaHei", Arial, Lucida Grande, Tahoma, sans-serif;
    width: 100%;
    height: 100%;
    padding: 0;
    margin: 0;
    overflow-x: hidden;
    overflow-y: hidden;
    }
    
    * {
    margin: 0;
    padding: 0;
    font-style: normal;
    font-weight: normal;
    }
</style>
</head>
<body><?php 
if ($yzm["loading_on"] == "on") {
	?><div class="loading" id="my-loading"><div class="pic"></div></div><?php 
}
?>

    <iframe src="analysis.php?v=<?php echo $url;?>" class="iframeStyle" id="myiframe" ></iframe>

</body></html>