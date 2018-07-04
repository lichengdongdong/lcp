<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>聚才林 一站式 全员招聘系统 </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.bootcss.com/minireset.css/0.0.2/minireset.min.css" rel="stylesheet">
    <link href="https://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="about/main.css" />
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="about/main.js"></script>
</head>



<body>
    <!--Header-->
    <header>
        <div class="container">
            <nav class="navbar navbar-default" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="about/images/logo.png"/></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                        <li class="" id="link-index">
                            <a href="about-index">首页</a>
                        </li>
                        <li id="link-product">
                            <a href="about-product">产品</a>
                        </li>
                        <li id="link-solution">
                            <a href="about-solution">解决方案</a>
                        </li>
                        <li id="link-about">
                            <a href="about-about">关于我们</a>
                        </li>
                    </ul>


                </div>
                <!-- /.navbar-collapse -->
            </nav>

        </div>
    </header>
    <!-- /Header-->

<?php
$module=$_GET['module'];
if(in_array($module,array('index','product','solution','about'))){
  require_once "about/{$_GET[module]}.html";
}
?>

<div class="footer">
<div class="container ">
    上海聚才令软件有限公司 版权所有  备案号: 沪ICP备18018975  ©2018 jucailin.com 
</div>
</div>
  
</body>
</html>

<script>
  $(function(){
      $("#link-<? echo $_GET['module']?>").addClass("active");
  })    
</script>




