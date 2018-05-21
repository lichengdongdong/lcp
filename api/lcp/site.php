<?
//
error_reporting ( E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED );
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');
header("Access-Control-Allow-Headers:content-type");


include "lib.php";
include "db_mysql.php";
include "baselib.php";


//

function look($var){
   if($_GET['debug']==1){
      echo "<pre>".print_r($var)."</pre>";
   }
   else{
	  $GLOBALS['look'] .= (var_export($var,true)."\n");
   }
}



/***/
function site_get_action($method,$to){
   $method=strtolower($method);
 
   //
   if($method=="get") {
   	  if($to=="all"){
   	  	return "find";
   	  }
   	  else{
   	  	return "get";
   	  }
   }
   //
   if($method=="post"){
   	  return "save";
   }
   //
   if($method=="delete"){
   	  return "delete";
   }
}




/**
 * Enter description here...
 *
 * @param unknown_type $cond
 * @param array $a
 * @return unknown
 */
function site_condition_sql($cond, array $a) {
   
   foreach ( $a as $key => $v ) {
      
      $cond [$key] = strval ( $cond [$key] );
      if ($cond [$key] == 'all') {
         continue;
      }
      
      if ($cond [$key] != '') {
         $sql_where .= ($v);
      }
   }
   
   return $sql_where;
}

/**
 * 
 * @param unknown_type $sql
 * @param unknown_type $input
 */
function site_multi_input($sql, $input, $type = " and ") {
   if ($input == '') {
      return;
   }
   $returnStr = "";
   $strs = explode ( " ", $input );
   $sqla = "";
   foreach ( $strs as $s ) {
      echo $s = trim ( $s );
      if ($s == '')
         continue;
         
      //    
      

      $singsql = ((($sqla != '') ? $type : "") . str_replace ( "{input}", $s, $sql ));
      
      if ($s == 'null' or $s = '!null') {
         $singsql = str_replace ( array ('like', '%', 'null' ), array ('=', '', '' ), $singsql );
      }
      
      if ($s [0] == "!") {
         $singsql = str_replace ( array ('!', 'like', '=', 'null' ), array ('', 'not like', '<>', '' ), $singsql );
      }
      
      $sqla .= ($singsql);
   }
   $returnStr .= (" and ( {$sqla} )");
   
   return $returnStr;
}
/**
 * Enter description here...
 *
 * @param unknown_type $oarray
 * @param unknown_type $order
 */
function site_order_sql($oarray, $orders) {
   if ($orders == '') {
      return '';
   }
   //look($orders);
   $orderArray = explode ( ",", $orders );
   //
   $orderSql = "";
   foreach ( $orderArray as $order ) {
      if ($_GET ['order'] != '') {
         $order = str_replace ( ":", " ", $_GET ['order'] );
      }
      
      //
      $orderA = explode ( " ", $order );
      if ($oarray [$orderA [0]] == '') {
         return '';
      }
      $orderA [0] = $oarray [$orderA [0]];
      //
      if ($orderA [1] == '' or ($orderA [1] != 'asc' and $orderA [1] != 'desc')) {
         $orderA [1] = "desc";
      }
      //
      if ($orderSql != "") {
         $orderSql .= (",");
      }
      //
      $orderSql .= ("{$orderA [0]} {$orderA[1]}");
   }
   //look($orderSql);
   //
   if ($orderSql != '') {
      return " order by {$orderSql}";
   }
}
/**
 * Enter description here...
 *
 * @param unknown_type $farray
 * @param unknown_type $field
 */
function site_field_sql($farray, $field) {
   $resturnStr = '';
   $fieldA = explode ( ",", $field );
   $isfirst = 1;
   
   foreach ( $fieldA as $fa ) {
      if (trim ( $farray [$fa] ) == '')
         continue;
      if ($isfirst == 0) {
         $resturnStr .= (",");
      }
      $resturnStr .= $farray [$fa];
      $isfirst = 0;
   }
   return $resturnStr;
}
