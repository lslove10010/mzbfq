<?php
// 获取当前脚本的绝对路径
$scriptPath = dirname(__FILE__);

// 构建 install.lock 文件的绝对路径
$lockFile = realpath($scriptPath . '/dmku/install/install.lock');

// 获取当前脚本的相对路径
$scriptRelativePath = dirname($_SERVER['PHP_SELF']);

// 输出调试信息（可选）
// echo "检查文件: " . $lockFile . "<br>";
// echo "当前脚本相对路径: " . $scriptRelativePath . "<br>";

// 检查当前脚本是否在根目录下
if ($scriptRelativePath !== '/') {
    // 当前脚本不在根目录下，提示用户将播放器放置到域名根目录下安装
    echo "你当前播放器放置位置不在网站根目录下，请将播放器放置到网站域名根目录下安装。";
    die();
}

// 检查文件是否存在
if (file_exists($lockFile)) {
    // 文件存在，表示已经安装，跳转到 player 页面
    $playerUrl = "http://" . $_SERVER['HTTP_HOST'] . "/player/?url=";
    header("Location: " . $playerUrl);
    die();
} else {
    // 文件不存在，表示未安装，跳转到安装页面
    $installUrl = "http://" . $_SERVER['HTTP_HOST'] . "/dmku/install/";
    header("Location: " . $installUrl);
    die();
}
?>