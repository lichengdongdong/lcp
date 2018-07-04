<?php

/**
编程机器人单文件版本   2018-04-24
**/

error_reporting ( E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED );

//
define("DATABASE_HOST","127.0.0.1");  //pdo 的 host 是 127.0.0.1 而不是  localhost
define("DATABASE_USER","root");
define("DATABASE_PASSWORD","123456");
define("DATABASE_DBNAME","jcl");

//数据库连接
$dsn="mysql:host=".DATABASE_HOST.";dbname=".DATABASE_DBNAME;
$pdo = new PDO($dsn, DATABASE_USER, DATABASE_PASSWORD);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

//
$table=$_POST['table'];
$type=$_POST['type'];
if($table!='' && $type!=''){
    //查询数据库
	$sql="select COLUMN_NAME as name,COLUMN_COMMENT as comment  from information_schema.columns where table_name='{$table}'";
	$stmt=$pdo->query($sql);
	print_r($stmt);
	$fields  = $stmt->fetchAll();

	//print_r($fields);

   
    //模板
	$templateCfg=getTemplateCfg();
	$template=$templateCfg[$type];


	$content="";
	foreach($fields as $f){

       //
       $replace['{fieldname}']=$f['name'];
       $replace['{fieldcomment}']=$f['comment'];
       
       //
       $content.= (str_replace(array_keys($replace), array_values($replace), $template)."\n");

	}
}
?>


<html>

<link rel="stylesheet" type="text/css" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"/>

<div class="container">
	
	<div class="row">

		<div class="col-md-12">

			<div class="panel">
				<div class="panel-heading">编程机器人</div>

				<form action="" method="post">

					<div class="form-group">
						<label for="exampleInputEmail1">表名</label>
						<input type="text" class="form-control" id="table" name="table" value="<? echo $table ?>"/>
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1">代码类型</label>
						<select class="form-control" name="type">
							<option value="vue-form">Vue-form</option>
						</select>
					</div>

					<div class="form-group">
						<button class="btn btn-info" type="submit">生成代码</button>
					</div>
				</form>



				<div class="form-group">
					<label for="exampleInputEmail1">生成代码</label>
					<textarea class="form-control" style="width:100%; height: 600px"><? echo $content ?></textarea>
				</div>



			</div>

		</div>

	</div>


</div>

</html>

<?php

function getTemplateCfg(){
  

   //vue
$templateCfg['vue-form']=
'<el-form-item label="{fieldcomment}">
	<el-input v-model="formData.{fieldname}"></el-input>
</el-form-item>';

  return $templateCfg;
} 


?>