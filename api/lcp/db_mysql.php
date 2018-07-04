<?php
$bt =$ebt= microtime ( true );
function db_history($text, $pdo) {
	global $bt,$ebt;
	
	$throwException = 0;
	$exet = round ( (microtime ( true ) - $ebt) * 1000, 3 ) . "ms";
	$ebt= microtime ( true );

	return;
	look($pdo->errorInfo);

	if ($pdo->errorCode() != 0) {
		look($pdo->errorInfo);
		$sqladd = "<br/>|{$pdo->errorInfo()}";
		$GLOBALS ['sqlError'] = 1;
		$throwException = 1;
	}
	$GLOBALS ['sqlhistory'] [] = "[{$exet}]" . $text . $sqladd;


	//
	if ($throwException == 1) {
		if (! preg_match ( "/Lost/", $pdo->errorInfo () )) {
			look($pdo->errorCode);
			$msg="mysql:{$pdo->errorInfo()}\n{$text}";
			$exp= new LcError ( "系统异常！请火速告诉内推君~".$msg);
			$exp->level=5;
			throw $exp;
		}
	}
}


function site_data_connect(){
	global $pdo;

	//
	if($pdo!=null){
		return $pdo;
	}

    //
	try{
		$dsn="mysql:host=".DATABASE_HOST.";dbname=".DATABASE_DSN;
		$array=array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8");
		$pdo = new PDO($dsn, DATABASE_USER, DATABASE_PASSWORD,$array); 
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	}catch(PDOException $e){
		exit($e->getMessage());
	}

	return $pdo;
}



/**
 * 执行数据库的返回数组操作
 */
function site_get_array($sql, $keyid = '', $getfield = '', $cachetime = 0) {
	
	if (! isset ( $sql )) {
		return;
	}
	//查询数据
	global $pdo;
	if ($pdo == null) {
		$pdo = site_data_connect();
	}

	$nowdb=$pdo;
	
	//
	//$sql=str_replace(array("\t","\n"),"",$sql);
	$content_array = array ();
	if (! preg_match ( "/limit/i", $sql ) and preg_match ( "/select/i", $sql )) {
		$sql .= " limit 0,5000";
	}
	
	//
	//look ( $cachetime );
	$cache = 1;
	if ($cachetime != 0) {
		$mc_key = "mc_sql_" . md5 ( $sql );
		$result = mc_get ( $mc_key );
		look ( $_GET ['nocache'] );
		if (! is_array ( $result ) or $_GET ['nocache'] == 1) {
			
			//
			$stmt=$nowdb->query($sql);
			$result  = $stmt->fetchAll();
			if ($result == '') {
				$result = array ();
			}
			
			$cache = 0;
			mc_set ( $mc_key, $result, $cachetime );
		}
	} else {
		$stmt =$nowdb->query($sql);
		$result  = $stmt->fetchAll();
		$cache = 0;
	}
	
	//look($result);
	
	
	//
	foreach ( $result as $value ) {
		if ($keyid != '') {
			if ($getfield != '') {
				$content_array [$value [$keyid]] = $value [$getfield];
			} else {
				$content_array [$value [$keyid]] = $value;
			}
		} else {
			if ($getfield != '') {
				$content_array [] = $value [$getfield];
			} else {
				$content_array [] = $value;
			}
		}
	}
	//
	unset ( $result );
	if ($cache == 0) {
		db_history ( $sql, $nowdb );
	}
	//
	if (count ( $content_array ) > 0) {
		return $content_array;
	} else {
		return array();
	}
}

/**
 * 数据表的更新操作
 * 用法：传入一个sql语句
 */
function site_data_update($sql, $showerror = 1) {
	global $pdo;
	if ($pdo == null) {
		$pdo = site_data_connect();
	}
	//
	//$sql=str_replace(array("\t","\n"),"",$sql);
	$pdo->exec($sql); 
    if ($pdo->errorCode () != 0) {
		$errors=$pdo->errorInfo();
		throw new LcError($errors[2]);
	}

	db_history ( $sql, $pdo );
	//
	return ($pdo->errorCode () != 0) ? false : true;
}

/**
 * 数据表记录的删除操作
 */
function site_data_delete($sql) {
	global $pdo;
	if ($pdo == null) {
		$pdo = site_data_connect();
	}
	//
	//$sql=str_replace(array("\t","\n"),"",$sql);
	$pdo->exec($sql); 
	db_history ( $sql, $pdo );
	//
	return ($pdo->errorCode () != 0) ? false : true;
}

/**
 * 数据表操作
 */
function site_data_insert($sql, $type = "") {
	global $pdo;
	if ($pdo == null) {
		$pdo = site_data_connect();
	}
	//$sql=str_replace(array("\t","\n"),"",$sql);
	//

	$pdo->exec ( $sql );

 //
	if ($pdo->errorCode () != 0) {
		$errors=$pdo->errorInfo();
		throw new LcError($errors[2]);
	}
	
	//
	if ($type == "increment") {
		$result = $pdo->lastInsertId ();
	}
	return $result;
}

/*
 * 事务开始进行
 */
function site_trans_begin() {
	if (! isset ( $GLOBALS ['committimes'] )) {
		$GLOBALS ['committimes'] = 1;
	} else {
		$GLOBALS ['committimes'] ++;
	}
	
	//
	if ($GLOBALS ['committimes'] > 1) {
		return;
	}
	
	//
	global $pdo;
	if ($pdo == null) {
		$pdo = new NeituiMysql ();
	}
	//
	$GLOBALS ['autocomment'] = 0;
	
	//
	$sql = "SET AUTOCOMMIT ={$GLOBALS['autocomment']}";
	$pdo->runSql ( $sql );
	db_history ( $sql, $pdo );
	
	//
	$sql = "BEGIN";
	$pdo->runSql ( $sql );
	db_history ( $sql, $pdo );

}
/**
 * 
 */
function site_trans_commit() {
	if (! isset ( $GLOBALS ['committimes'] ) or $GLOBALS ['committimes'] < 1) {
		return;
	}
	//
	if ($GLOBALS ['committimes'] > 1) {
		$GLOBALS ['committimes'] --;
		return;
	}
	
	look ( "autocomment:{$GLOBALS ['autocomment']}" );
	//
	if ($GLOBALS ['autocomment'] != 0 or $GLOBALS ['rollback_commit'] == 1) {
		return;
	}
	//
	global $pdo;
	if ($pdo == null) {
		$pdo = new NeituiMysql ();
	}
	//
	$sql = ($GLOBALS ['sqlError'] == 1) ? "ROLLBACK" : "COMMIT";
	//
	$pdo->runSql ( $sql );
	db_history ( $sql, $pdo );
	
	$sql = "SET AUTOCOMMIT =1";
	$pdo->runSql ( $sql );
	db_history ( $sql, $pdo );
	
	//
	$GLOBALS ['rollback_commit'] = 1;
}
?>