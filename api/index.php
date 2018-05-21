<?php
/*
 * 发送：
 * GET  http://localhost/restful/class  列出所有班级
 * GET  http://localhost/restful/class/1    获取指定班的信息
 * POST http://localhost/restful/class?name=SAT班&count=23 新建一个班
 * PUT  http://localhost/restful/class/1?name=SAT班&count=23  更新指定班的信息（全部信息）
 * PATCH  http://localhost/restful/class/1?name=SAT班    更新指定班的信息（部分信息）
 * DELETE  http://localhost/restful/class/1 删除指定班
*/
include "config.php";
include "lcp/site.php";


include "entity/jsconfig.php";
include "entity/resume.php";
include "entity/resumelog.php";
include "entity/resumeorig.php";
include "entity/resumework.php";



try{

//获得基本参数
	$entity=$_GET['entity'];
	$to=$_GET['to'];
	$method=$_SERVER['REQUEST_METHOD'];
	$action=site_get_action($method,$to);


//调用文件
	$controllerFile="controller/{$entity}.php";
	include $controllerFile;

//执行文件
	$controllerName=ucfirst ( $entity )."Controller";
	$controller=new $controllerName();
	look($action);

	call_user_func ( array ($controller, $action ));
	
}catch(LcError $e){
	$e->show();
}


