<link rel="stylesheet" href="./css/bootstrap-3.4.1/dist/css/bootstrap.min.css"">
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="">觅知JSON直解专业版弹幕播放器后台管理中心 - www.98dou.cn </a> </div>
    <div class="collapse navbar-collapse" id="example-navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
      <?php if(php_self() == 'index.php'){echo '<li class="active">';}else{echo '<li>';}?><a href="index.php"><span class="glyphicon glyphicon-list-alt"></span>网站信息</a></li>
      <?php if(php_self() == ''){echo '<li class="active">';}else{echo '<li>';}?><a href="https://www.98dou.cn/4312.html"target="_blank"><span class="glyphicon glyphicon-refresh"></span>更新播放器</a></li>
            <?php if(php_self() == ''){echo '<li class="active">';}else{echo '<li>';}?><a href="https://www.98dou.cn/"target="_blank"><span class="glyphicon glyphicon-globe
"></span>觅知博客</a></li>
      
      <?php if(php_self() == 'exit.php'){echo '<li class="active">';}else{echo '<li>';}?><a href="exit.php"><span class="glyphicon glyphicon-off"></span>安全退出</a></li>
      </ul>
    </div>
  </div>
</nav>
<?php
function php_self(){
      $php_self=substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'],'/')+1);
      return $php_self;
  }
?>