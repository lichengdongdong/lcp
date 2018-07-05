<?php
/* Smarty version 3.1.32, created on 2018-07-05 16:20:01
  from '8a63d6b7b4b921db9dc2983a0c6cb8cf2759a6e2' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5b3dd4b14eca95_29867833',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b3dd4b14eca95_29867833 (Smarty_Internal_Template $_smarty_tpl) {
echo '<?php

';?>class <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
 extends Module {
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['fields'], 'f');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
?>
	public $<?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
; //<?php echo $_smarty_tpl->tpl_vars['f']->value['comment'];?>

<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
	
   /**
	 * <?php echo $_smarty_tpl->tpl_vars['data']->value['tablecomment'];?>
验证入库操作
	 */
   function validate() {
   	$errors = array ();


<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['fields'], 'f');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
?>
  <?php if ($_smarty_tpl->tpl_vars['f']->value['name'] <> 'id') {?>
   	if(<?php if ($_smarty_tpl->tpl_vars['f']->value['type'] == 'int') {?>intval<?php } elseif ($_smarty_tpl->tpl_vars['f']->value['type'] == 'varchar' || $_smarty_tpl->tpl_vars['f']->value['type'] == 'text') {?>trim<?php }?>($this-><?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
)==<?php if ($_smarty_tpl->tpl_vars['f']->value['type'] == 'int') {?>0<?php } elseif ($_smarty_tpl->tpl_vars['f']->value['type'] == 'varchar' || $_smarty_tpl->tpl_vars['f']->value['type'] == 'text') {?>''<?php }?>){
   	   $errors['<?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
']="<?php echo $_smarty_tpl->tpl_vars['f']->value['comment'];?>
不能为空";
   	}
  <?php }
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>


   	if (count ( $errors ) > 0) {
   		$exp= new LcError ( "录入错误" );
   		$exp->errors=$errors;
   		throw $exp;
   	}
   }

	/**
	 * <?php echo $_smarty_tpl->tpl_vars['data']->value['name'];?>
插入数据库操作
	 * @param <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
 $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
 <?php echo $_smarty_tpl->tpl_vars['data']->value['tablecomment'];?>
实体 
	 * @return <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>

	 */
	static function save(<?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
 $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
,$isvalide=0) {
		//基本类信息验证 
		$<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->validate ();
		
		//验证程序代码段
		
		
		//是否是只做验证
		if($isvalide==1){
			return true;
		}		
		if(intval ( $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->id ) != 0){

		//数据更新			
			$sql = "update <?php echo $_smarty_tpl->tpl_vars['data']->value['table'];?>
 set
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['fields'], 'f', false, 'i');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value => $_smarty_tpl->tpl_vars['f']->value) {
?>

              <?php if ($_smarty_tpl->tpl_vars['f']->value['name'] != 'id') {?>
			     <?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
='".<?php if ($_smarty_tpl->tpl_vars['f']->value['type'] == 'int') {?>intval<?php } elseif ($_smarty_tpl->tpl_vars['f']->value['type'] == 'varchar' || $_smarty_tpl->tpl_vars['f']->value['type'] == 'text') {?>addslashes<?php }?>($<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
-><?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
)."'<?php if ($_smarty_tpl->tpl_vars['data']->value['fields'][$_smarty_tpl->tpl_vars['i']->value+1] != '') {?> ,<?php }?>
              <?php }?>
			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

			where id='<?php echo $_smarty_tpl->tpl_vars[''.($_smarty_tpl->tpl_vars['data']->value['object'])]->value->id;?>
' limit 1";
			if (! site_data_update ( $sql )) {
				throw new LcError ( "更新<?php echo $_smarty_tpl->tpl_vars['data']->value['tablename'];?>
数据失败" );
			}

		//tocache
		//$<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->setPrimaryKey ( "id" );
		//$<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->toCache ();
			return $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
;


		}


		
		//数据添加
		$<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->createtime=$<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->createdate=date("Y-m-d H:i:s");
		$sql = "insert into <?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
 set 
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['fields'], 'f', false, 'i');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['i']->value => $_smarty_tpl->tpl_vars['f']->value) {
?>

              <?php if ($_smarty_tpl->tpl_vars['f']->value['name'] != 'id') {?>
			     <?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
='".($<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
-><?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
)."'<?php if ($_smarty_tpl->tpl_vars['data']->value['fields'][$_smarty_tpl->tpl_vars['i']->value+1] != '') {?> ,<?php }?>
              <?php }?>
			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            ";

		$<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->id = site_data_insert ( $sql, 'increment');
		if ($<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->id == 0) {
			throw new LcError ( "插入<?php echo $_smarty_tpl->tpl_vars['data']->value['tablecomment'];?>
错误" );
		}
		//tocache
		//$astepDay->setPrimaryKey ( "id" );
		//$astepDay->toCache ();
		return $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
;
	}
	
	
	
	/**
	 * 查找某条信息
	 *
	 * @param  id
	 * @return <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>

	 */
	static function getById($id) {
		$id = intval ( $id );
		if ($id == 0){
			throw new LcError ( "还没有输入<?php echo $_smarty_tpl->tpl_vars['data']->value['tablecomment'];?>
 ( <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
 )编号" );
		}
		
		/* 是否开启缓存
		$re = self::getCache ( $id );
		if ($re != null) {
			return $re;
		}
		*/
		
		$sql = "select a.* from <?php echo $_smarty_tpl->tpl_vars['data']->value['table'];?>
 a where a.id='{ $id}'  limit 1";
		$result = site_get_array ( $sql );

		
		if (count($result) == 0) {
			return null;
		}



		$<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
= new <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
 ( $result [0] );

		
		/* 是否开启缓存
		$<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->setPrimaryKey ( "id" );
		$<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->toCache ();
		*/
		
		return $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
;
	}
	
	/**
	 * <?php echo $_smarty_tpl->tpl_vars['data']->value['tablecomment'];?>
查找操作
	 *
	 * @param array $c 条件
	 * @param  $page 页面
	 * @param  $para 参数
	 * @param  $order  排序
	 * @return Page
	 */
	static function find(array $c, $page = 1, $para = "a.*", $order = "id desc") {

		
		//组装条件
        
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['fields'], 'f');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
?>
		  $carray ['<?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
'] = " and a.<?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
 = '" . $c ['<?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
'] . "'";
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		

         //允许的排序
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['fields'], 'f');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
?>
        $oarray ['<?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
']="a.<?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
";
        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
		
	

        //获得排序Sql
		$orderSql=site_order_sql($oarray,$order);

        /*
        //显示其他表数据
        //显示其他表数据
		if($c['showField']==1){
		    $para.=",t.field";
			$leftJoinSql.=" LEFT JOIN table t ON a.id=t.id ";
		}
		*/
		
		
		//group by
		$groupBySql="";
		if($c['groupBy']!=''){
			$groupBySql=" GROUP BY <?php echo $_smarty_tpl->tpl_vars['c']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_groupBy']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_groupBy']->value['index'] : null)];?>
 ";
			$orderSql="";
		}

		
		$whereSql=site_condition_sql ( $c, $carray );
		
		//组装sql语句
		$sql = "select { $para } from <?php echo $_smarty_tpl->tpl_vars['data']->value['table'];?>
 a  { $leftJoinSql } where 1 { $whereSql } { $groupBySql } { $orderSql }";
		
		//分页的情形
		if (get_class($page) == "Page") {
			$page->lc_page_array ( $sql, $_GET ['page'],$c ['key'], $c ['value'],$c['cache']);
			$page->setPageAdd ( $c, $carray );
			return $page;
		}
		//直接返回
		if(intval($page)!=0){
			$limit=" limit 0,".intval($page);
		}
		
		$result = site_get_array ( $sql . $limit, $c ['key'], $c ['value'],$c['cache'] );
		return $result;
	}


}<?php }
}
