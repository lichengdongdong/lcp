<?php

/**
 * 成功信息类
 *
 */
class Success {
	public $message = "success"; // 异常信息
	//public $title = ''; // 异常信息
	public $className = "success";
	public $code = 1;
	public $js;
	public $jump = '';
	public $extcontent;
	function __construct($message = null, $code = 0) {
		$this->message = $message;
	}
	
	/*
	 * 添加链接
	 */
	function addLink($url, $str) {
		$this->links [] = array ('url' => $url, 'str' => $str );
	}
	
	function getMessage() {
		return $this->message;
	}
	/**
	 * Enter description here...
	 *
	 * @return unknown
	 */
	function getObject() {
		$a = array ();
		$a ['type'] = "success";
		$a ['message'] = $this->message;
		return $a;
	}
	/*
	 *
	 */
	function showMessage($jump = 1, $nolink = 0) {
		$GLOBALS ['showmessagenolink'] = $nolink;
		$this->jump = str_replace ( "&json=1", "", $this->jump );
		
		$GLOBALS ['success'] = 1;
		//收集数据
		//DA::get ();
		

		//如果js为reload
		if ($this->js == "reload") {
			$this->js = "parent.b.hide();parent.location.reload();";
		}
		
		//如果是开发环境
		if (SYSTEM_DEVELOP == 1) {
			$this->message = $GLOBALS ['look'] . $this->message;
			if (in_array ( $GLOBALS ['access'], array ('json', 'jsonp' ) )) {
				$this->message .= ("<br/>" . implode ( "<hr />", $GLOBALS ['sqlhistory'] ));
			}
		}
		
		//如果是json
		if (in_array ( $GLOBALS ['access'], array ('json', 'jsonp' ) )) {
			//header ( 'Content-type: application/json' );
			unset ( $this->js, $this->jump, $this->links );
			
			if (strpos ( $_SERVER ["HTTP_USER_AGENT"], "MSIE" ) != true) {
				header ( 'Content-type: application/json' );
			}
			
			if ($_SESSION ['frommobile'] == 1) {
				$this->message = str_replace ( array ("<br/>", "<br>" ), "\n", $this->message );
				$this->message = strip_tags ( $this->message );
			}
			$out = json_encode ( site_string_filter ( $this, 1 ) );
			if ($GLOBALS ['access'] == 'jsonp' and $_GET ['callback'] != '') {
				$out = $_GET ['callback'] . "(" . $out . ")";
			}
			echo $out;
			exit ();
		}
		
		//如果是xml
		if ($GLOBALS ['access'] == 'xml') {
			echo '<?xml version="1.0" encoding="utf-8"?>';
			echo "\n<ccsc>\n<result>SUCCESS</result>\n<detail> {$this->message}</detail>\n</ccsc>";
			exit ();
		}
		
		//跳转页面
		if ($jump == 1) {
			$_SESSION ['showmessage'] = $this->message;
			//
			$url = $this->jump != '' ? $this->jump : $_SERVER ['REQUEST_URI'];
			location ( "{$url}" );
			exit ();
		}
		
		//普通模式 如果有跳转
		if ($this->jump != '') {
			$jsadd .= ("site_jump(1,'{$this->jump}')");
		}
		
		//如果有其他js
		if ($this->js != '') {
			$jsadd .= ($this->js);
			$_SESSION ['session_exejs'] = '$(function(){' . $jsadd . '})';
		}
		
		$jumpinfo ['url'] = $this->jump;
		$jumpinfo ['text'] = $this->jumptext;
		$jumpinfo ['autojump'] = $jump;
		//
		site_prompt ( $this->title, $this->message, $jumpinfo, $this->extcontent );
		echo '<script>$(function(){$(".content").prepend($(".exceptionMsg"));' . $jsadd . '})</script>';
	}
	
	/*
	 *
	 */
	function getClassName() {
		return $this->className;
	}
	
	/**
	 * 最快速的构造一个success
	 */
	static function show($msg) {
		$s = new Success ( $msg );
		$s->showMessage ( 0 );
		return;
	}
}

/**
 * Enter description here...
 * 网站异常类
 */
class SiteException extends Exception {
	public $message = 'Unknown exception'; // 异常信息
	public $detail = 'Unknown exception'; // 异常信息
	public $code = 0;
	public $className = "exception";
	public $js = '';
	public $jump = '';
	public $level = 0; //0 是一般,1 稍微紧急 ,2特别紧急
	public $type = 0; //0 默认对外  1:系统
	public $iflog = 1;
	public $links = array ();
	public $error = array ();
	
	/**
	 * 
	 * @param $message
	 * @param $detail
	 * @param $level
	 * @param $hasqq
	 * @return SiteException
	 */
	function __construct($message, $detail = '', $code = 0, $type = 0) {
		//
		$GLOBALS ['sqlError'] = 1;
		site_trans_commit ();
		$this->detail = $detail;
		$this->code = $code;
		$this->iflog = 1;
		$this->message = $message;
		$this->hasqq = $hasqq;
		$this->type = $type;
		if (ERROR_BY_EXCEPTION == 1) {
			//throw $this;
		}
		look ( $detail );
		
		//微信接口
		if ($_SERVER ['HTTP_USER_AGENT'] == "Mozilla/4.0") {
			mclook ( $message );
		}
		
		//记录异常
		$mo = trim ( $GLOBALS ['module'] );
		$mos = array ('neitui', 'job', 'user', 'mail', 'message', 'company', 'cooper', 'devapi', 'edm', 'file', 'qnt', 'onekey', 'mobile', 'resume', 'site', 'spe', 'trade', 'weixin', 'wechat', 'wxpay' );
		if (in_array ( $mo, $mos )) {
		
		}
		$module = "{$GLOBALS['module']}-e";
		return $this;
	}
	/*
	 * 添加链接
	 */
	function addLink($url, $str) {
		$this->links [] = array ('url' => $url, 'str' => $str );
	}
	
	/*
	 *
	 */
	function getClassName() {
		return $this->className;
	}
	/**
	 * 
	 */
	function showMessage() {
		
		//收集数据
		//DA::get ();
		

		//
		if ($_SERVER ['PHP_SELF'] == '/user.php') {
			//site_createLog($module = "userexception", $var=date("YmdHis").":".$this->message.",url:{$_SERVER['REQUEST_URI']},uid:".$_SESSION['uid']) ;
		}
		
		if ($GLOBALS ['access'] == "xml") {
			note ( "Exception:" . $this->message );
		}
		
		//加链接
		if (count ( $this->links ) > 0) {
			$linkhtml = '<ul>';
			foreach ( $this->links as $key => $link ) {
				$linkhtml .= ('<li>&lt;&lt;<a href="' . $link ['url'] . '">' . $link ['str'] . '</a></li>');
			}
			$linkhtml .= ('</ul>');
			$this->message .= ($linkhtml);
		}
		
		//json
		//look(SYSTEM_DEVELOP);
		

		//look()
		

		if (SYSTEM_DEVELOP == 1) {
			$this->message = $GLOBALS ['look'] . $this->message;
			$this->message = ($this->message) . "<br/>" . str_replace ( '#', "<br/>", $this->getTraceAsString () );
			if (in_array ( $GLOBALS ['access'], array ('json', 'jsonp' ) )) {
				$this->message .= ("<br />" . implode ( "<hr />", $GLOBALS ['sqlhistory'] ));
			}
		}
		
		//
		if (in_array ( $GLOBALS ['access'], array ('json', 'jsonp' ) )) {
			header ( 'Content-type: application/json' );
			unset ( $this->js, $this->jump, $this->links );
			
			if (SYSTEM_DEVELOP == 1) {
				$this->message .= ($GLOBALS ['look']);
			}
			
			if ($_SESSION ['frommobile'] == 1) {
				$this->message = ($this->message);
			}
			
			$out = json_encode ( site_string_filter ( $this, 1 ) );
			if ($GLOBALS ['access'] == 'jsonp' and $_GET ['callback'] != '') {
				$out = $_GET ['callback'] . "(" . $out . ")";
			}
			echo $out;
			exit ();
		}
		
		if ($GLOBALS ['access'] == 'xml') {
			echo '<?xml version="1.0" encoding="utf-8"?>';
			echo "\n<ccsc>\n<result>FAILURE</result>\n<detail>{$this->message}</detail>\n</ccsc>";
			exit ();
		}
		
		//普通模式 如果有跳转
		if ($this->jump != '') {
			$jsadd .= ("site_jump(1,'{$this->jump}')");
		}
		
		//如果有其他js
		if ($this->js != '') {
			$jsadd .= ($this->js);
			$_SESSION ['session_exejs'] = '$(function(){' . $jsadd . '})';
		}
		
		$jumpinfo ['url'] = $this->jump;
		$jumpinfo ['text'] = $this->jumptext;
		$jumpinfo ['autojump'] = $jump;
		//
		site_error_message ( $this->title, $this->message, $jumpinfo, $this->extcontent, $this->type );
		echo '<script>$(function(){ ' . $jsadd . '})</script>';
	}
	
	function getObject() {
		$a = array ();
		$a ['type'] = "error";
		$a ['code'] = parent::getCode ();
		$a ['message'] = $this->getMessage ();
		return $a;
	}
}

/**
 * 模块基础类
 *
 */
class Module {
	private $field;
	private $primaryKey;
	/*
	 *
	 */
	function __construct($o = '') {
		
		if (gettype ( $o ) != 'array') {
			$o = ( array ) $o;
		}
		


		if ($this->field != '' and ERROR_BY_EXCEPTION == 0) {
			foreach ( $this->field as $key => $f ) {
				$eval = '@$this->set' . ucfirst ( $key ) . '($o[\'' . $key . '\']);';
				eval ( $eval );
			}
			return;
		}
		
		//
		foreach ( $this as $key => $value ) {
			if (gettype ( $o [$key] ) == 'string') {
				$o [$key] = trim ( $o [$key] );
			}
			$this->$key = ($o [$key]);
			//	echo $key.":".$value;
		}
	
	}
	/**
	 * 
	 */
	function getfieldsql() {
		$sql = "";
		
		$fs = (get_class_vars ( get_class ( $this ) ));
		//look ( $fs );
		foreach ( $fs as $key => $f ) {
			if (in_array ( $key, array ("field", "primaryKey" ) )) {
				continue;
			}
			$sql .= " {$key}='" . mysql_escape_string ( $this->$key ) . "',";
		}
		return $sql = trim ( $sql, ',' );
	}
	
	/*
	 * 直接把页面的POST转换为需要的数组
	 */
	function transToArray($var) {
		$ra = array ();
		foreach ( $this->field as $key => $f ) {
			$ra [$key] = $var [$key];
		}
		return $ra;
	}
	
	/*
	 *
	 */
	function compare($o) {
		$error = 0;
		foreach ( $this as $key => $value ) {
			if ($key == 'field') {
				continue;
			}
			//
			echo "<br>";
			if ($this->$key != $o->$key) {
				echo "error:";
				$error ++;
			}
			echo $key . ":" . $this->$key . "=" . ($o->$key);
		}
		echo "<br>have {$error} not compare!";
	}
	
	/*
	 *
	 */
	function update($a) {
		if (gettype ( $a ) == 'object') {
			$a = ( array ) $a;
		}
		foreach ( $a as $key => $value ) {
			$key = trim ( $key );
			if (trim ( $key ) == "Modulefield") {
				continue;
			}
			
			//look($key);
			if (! property_exists ( $this, $key )) {
				continue;
			}
			
			//look("--".$key."--"); 
			if (is_array ( $value )) {
				$this->$key = ($a [$key]);
			} else {
				$this->$key = trim ( $a [$key] );
			}
		}
		return $this;
	}
	
	//
	static function transit($str) {
		return $str;
		//return htmlspecialchars ( $str );
	}
	
	/**
	 * cache
	 */
	function setPrimaryKey($keyname) {
		//look("pkey:".$keyname);
		$this->primaryKey = $keyname;
	}
	
	//实体进cache
	function toCache($time = 5000) {
		//look("tocache{$this->primaryKey}");
		if ($this->primaryKey == '') {
			return false;
		}
		//
		$primaryKey = $this->primaryKey;
		$pid = trim ( $this->$primaryKey );
		$key = "entity-" . strtolower ( get_class ( $this ) ) . "-" . $pid;
		//look($key);
		

		mc_set ( $key, $this, $time );
		return true;
	
	}
	//根据id获得实体
	static function getCache($id) {
		$key = "entity-" . strtolower ( get_called_class () ) . "-" . $id;
		look ( $key );
		$re = mc_get ( $key );
		if ($re == '') {
			return null;
		}
		return $re;
	}
	
	/**
	 * 统一验证对象
	 * @param $moduleObjects
	 * @param $errorExts   
	 * errorExs=array('filed'=>"这个字段的数据不能为空",'field2'=>"这个字段的数据有问题");
	 */
	static function validates($moduleObjects, $errorExts = '') {
		//
		$errors = array ();
		//
		if (is_array ( $errorExts )) {
			$errors = $errorExts;
		}
		
		//
		$messages = "";
		foreach ( $moduleObjects as $object ) {
			try {
				call_user_func ( array ($object, "validate" ) );
			} catch ( SiteException $se ) {
				look ( $se );
				$messages .= "<br/>{$se->message}";
				$errors = array_merge ( $errors, $se->error );
			}
		}
		
		//
		if (count ( $errors ) > 0) {
			$exp = new SiteException ( implode ( "<br/>", $errors ) );
			$exp->error = $errors;
			throw $exp;
		}
	}
	
	/**
	 * 添加错误
	 */
	protected static function addError($error) {
		if (! is_array ( $GLOBALS ['error'] )) {
			$GLOBALS ['error'] = array ();
		}
		$GLOBALS ['error'] = array_merge ( $GLOBALS ['error'], $error );
	}
	
	/**
	 * @return 
	 */
	static function isNewModule() {
		//
		

		if (isset ( $GLOBALS ['isnewmodule'] )) {
			if ($GLOBALS ['isnewmodule'] == 1) {
				return true;
			} else {
				return false;
			}
		}
		
		//
		include 'lcp/moduleswitch.php';
		
		//
		$key = "{$GLOBALS['role']}-{$GLOBALS ['module']}";
		
		$switchHandleStr = $module_switch [$key];
		
		if ($switchHandleStr != '') {
			$switchHandles = explode ( ",", strtolower ( $switchHandleStr ) );
			$handle = strtolower ( $GLOBALS ['handle'] );
			if (in_array ( $handle, $switchHandles )) {
				$GLOBALS ['isbootstrap'] = 1;
				$GLOBALS ['isnewmodule'] = 1;
				
				$app_router = "{$key}-{$handle}";
				$GLOBALS ['app_router'] = $module_app_router [$app_router];
				return true;
			}
		}
		//
		$GLOBALS ['isnewmodule'] = 0;
		return false;
	}
	/**
	 * 获得模块的路径
	 * licheng
	 */
	static function getModulePath() {
		//
		$moduleIndex = "module";
		if (self::isNewModule ()) {
			$moduleIndex = "module";
		}
		//
		return "{$moduleIndex}/{$GLOBALS['module']}/";
	}
	
	/**
	 * 
	 */
	static function setClassPath($module) {
		
		if ($GLOBALS ['module'] != 'robot') {
			return;
		}
		
		//
		if ($_SESSION ['exist'] == '') {
			$_SESSION ['exist'] = get_declared_classes ();
			return;
		}
		
		$now = get_declared_classes ();
		$_SESSION ['moduleclass'] [$module] = array_diff ( $now, $_SESSION ['exist'] );
		
		//
		$_SESSION ['exist'] = $now;
	
	}
}

/**
 * Enter description here...
 *
 */
class Controller {
	
	private $isbootstrap;
	private $access;
	private $headerfile;
	
	/**
	 * 是否有子头部
	 */
	function haveSubHeader($default = 1) {
		$GLOBALS ['subheader'] = $default;
	}
	
	/**
	 * 
	 * @param unknown_type $value
	 */
	public function setIsBootstrap($type = "") {
		$this->isbootstrap = $GLOBALS ['isbootstrap'] = 1;
		if ($type == "old") {
			$GLOBALS ['bootstrapheader'] = "old";
		}
	}
	
	/**
	 * 
	 * @param unknown_type $value
	 */
	public function setIsMobile($value = 1) {
		look ( "ismobile--" );
		$this->ismobile = $GLOBALS ['ismobile'] = 1;
	}
	
	/**
	 * 
	 * @param unknown_type $value
	 */
	public function setIsH5($value = 1) {
		look ( "ish5--" );
		$this->ish5 = $GLOBALS ['ish5'] = 1;
	}
	/**
	 * 
	 * @param unknown_type $access
	 */
	public function setAccess($access) {
		$this->access = $GLOBALS ['access'] = $access;
	}
	
	/*
	 * 
	 */
	public function setHeader($headerfile) {
		$this->headerfile = $GLOBALS ['headerfile'] = $headerfile;
	}
	
	/**
	 * 
	 * @param unknown_type $location
	 */
	public function location($location, $message) {
		$location = str_replace ( "&amp;", "&", $location );
		location ( $location );
	}
	
	/**
	 * 是否是json
	 */
	public function isJSON() {
		return $GLOBALS ['access'] == 'json' ? 1 : 0;
	}

}

/**
 * 
 * @author licheng
 *
 */
class ApiController extends Controller {
	
	public $success;
	
	/**
	 * 构造
	 */
	function __construct() {
		$this->success = new Success ( "ok" );
	}
	
	/**
	 * 显示数据
	 */
	function show() {
		$this->success->showMessage ( 0 );
	}
	
	/**
	 * 获得用户服务的用户编号
	 */
	function getUserServeUid() {
		$uid = intval ( $_GET ['uid'] );
		$GLOBALS ['serveuid'] = $uid;
		//
		if ($uid == 0) {
			throw new SiteException ( "还没有uid" );
		}
		//
		if (User::getById ( $uid ) == null) {
			throw new SiteException ( "用户不存在" );
		}
		
		return $uid;
	}
	
	/**
	 * 今天是否执行过唤醒
	 */
	function haveWaken($day = "") {
		
		if ($day == '') {
			$begindate = date ( "Y-m-d" );
		} else {
			$begindate = date ( "Y-m-d", strtotime ( $day ) );
		}
		
		//
		$serv = str_replace ( "us_", "", "{$GLOBALS['module']}.{$GLOBALS['handle']}" );
		look ( $serv );
		
		//
		$logs = UserServeLog::find ( array ('uid' => $GLOBALS ['serveuid'], 'serve' => $serv, 'fromdate' => $begindate ), 1 );
		if ($logs == 0) {
			return false;
		}
		return true;
	}
	
	/**
	 * 
	 * @param $uid
	 */
	function recodeWaken($result) {
		$serv = str_replace ( "us_", "", "{$GLOBALS['module']}.{$GLOBALS['handle']}" );
		UserServeLog::recode ( $GLOBALS ['serveuid'], $serv, date ( "Y-m-d" ), $result );
	}
}



class Order {
	public $url;
	public $name = '字段';
	public $orderArray = array ();
	
	function addOrder($str) {
		$array = explode ( ":", $str );
		if ($array [0] == '') {
			return $this;
		}
		$this->orderArray [($array [0])] = ($array [1] == '' or ($array [1] != 'desc' and $array [1] == 'asc')) ? "asc" : $array [1];
		return $this;
	}
	//
	function addName($name) {
		$this->name = $name;
		return $this;
	}
	
	function addUrl($url) {
		$this->url = $url;
		return $this;
	}
	//
	function showHtml() {
		$orderlink = "";
		
		return '<a href="' . $this->url . '"> ' . ($this->name) . '</a>';
	}
}

class Page {
	var $db = null;
	
	public $className = "Page";
	public $result = array ();
	public $sum_row; //所查数据库的所有行数
	public $sum_page; //总共有多少行，有class内部计算得出
	public $num_everypage = 15; //每一页显示的行数
	public $notInLinkCond;
	public $itemSelect = 1;
	public $haveBeginEnd = 1;
	public $haveTotal = 1;
	public $needCount = 1;
	/*
	 * 为显示的各条信息链向的处理页面
	 */
	public $pagename = '&&';
	
	public $now_page;
	
	//-------
	public $page_link_sign = '';
	public $page_link_space = ''; //为当前页面.
	public $noresulttxt = "";
	
	public $pageBetween = 10; //多少页分段
	

	public $useReWrite; //多少页分段
	public $pageOrder = array ();
	
	public $itemNumUrlAdd = "";
	public $orderUrlAdd = "";
	public $itemNumList = array (10 => 10, 20 => 20, 50 => 50, 100 => 100, 500 => 500 );
	
	public $errormsg = "";
	//---基本文字
	

	//锚点
	public $anchor = "";
	
	function Page($pagename = '', $notInLinkCond = '') {
		if ($pagename == "" or $pagename == "nowpage") {
			$pagename = "{$_SERVER['SCRIPT_NAME ']}?name={$GLOBALS['module']}&handle={$GLOBALS['handle']}";
		}
		
		/*初始化数据库*/
		
		if ($pagename != '') {
			$this->pagename = $pagename;
		}
		//
		

		if ($notInLinkCond != '') {
			$this->notInLinkCond = $notInLinkCond;
		}
		
		if (trim ( $_GET ['order'] ) != '') {
			$this->orderUrlAdd = ('&order=' . $_GET ['order']);
			//添加一个order的参数，方便调用排序
			$orderArr = explode ( ":", $_GET ['order'] );
			$this->orderUrlArr ['order_i'] = $orderArr [0];
			$this->orderUrlArr ['order_b'] = $orderArr [1];
		}
	}
	
	/**
	 * @param unknown_type $cond
	 */
	function setPageAdd($condi, $sqlCond) {
		$a = explode ( ",", $this->notInLinkCond );
		
		//look ( $condi, 0, '------$condi---------' );
		//look ( $sqlCond, 0, '------$sqlCond------' );
		foreach ( $sqlCond as $key => $v ) {
			if (in_array ( $key, $a ) or $condi [$key] == '') {
				continue;
			}
			$this->pagename .= ("&" . $key . "=" . urlencode ( $condi [$key] ));
		}
	
	}
	/**
	 * 添加排序
	 *
	 * @param unknown_type $field
	 */
	function addOrder($field) {
		
		if ($field == '') {
			return "#";
		}
		$order = $_GET ['order'];
		if ($order == '' || empty ( $order )) {
			$order = "desc";
		}
		
		//每页多少行
		if (intval ( $_GET ['itemnum'] ) != 0) {
			$itemnumAdd = '&itemnum=' . $_GET ['itemnum'];
		}
		//
		

		$ordera = explode ( ":", $order );
		$order = $ordera [1];
		if (empty ( $order )) {
			$order = "desc";
		}
		if ($ordera [0] != $field) {
			if ($this->anchor) {
				$order .= "#{$this->anchor}";
			}
			return ("{$this->pagename}{$itemnumAdd}&order={$field}:{$order}");
		}
		
		if ($order == 'desc') {
			$order = "asc";
		} else {
			$order = "desc";
		}
		if ($this->anchor) {
			$order .= "#{$this->anchor}";
		}
		return ("{$this->pagename}{$itemnumAdd}&order={$field}:{$order}\" class=\"{$order}");
	}
	/**
	 * 或获取数据库的信息，通过一个sql语句
	 */
	function lc_page_array($sql, $now_page, $keyid = '', $getfield = '', $cachetime = 0) {
		//look ( "cachetime:{$cachetime}" );
		//
		if (is_array ( $this->linkCondition ) and count ( $this->linkCondition ) != 0) {
			foreach ( $this->linkCondition as $key ) {
				if ($_GET [$key] != '') {
					$this->pagename .= ("&" . $key . "=" . $_GET [$key]);
				}
			}
		}
		//
		if (SYSTEM_DEVELOP == 1) {
			echo $sql . "<br/>";
		}
		if ($now_page != '') {
			$this->now_page = $now_page;
		} else {
			$this->now_page = 1;
		}
		
		//
		if ($sql == '') {
			$error = "还没有sql语句！";
			if (ERROR_BY_EXCEPTION == 1) {
				throw new SiteException ( $error );
			}
			echo $error;
			return false;
		}
		//
		

		//
		//sql分页匹配不兼容
		

		if (substr_count ( $sql, ' from' ) == 1) {
			$sqltrans = preg_replace ( '/select(.+) from (.+)/is', 'select count(0) as cnt from $2', $sql );
		} else {
			$sqltrans = preg_replace ( '/select((?:(?!from).)*) from (.+)/is', 'select count(0) as cnt from $2', $sql );
		}
		// look ( $sqltrans, "trans" );
		$sqltrans = preg_replace ( '/(.+)order by(.+)/is', '$1', $sqltrans );
		// look($sqltrans);
		$count = 0;
		
		if (preg_match ( "/group by/i", $sql )) {
			$sqltrans = "SELECT count(0) as cnt FROM ( {$sqltrans} ) table1 ";
		}
		//
		if ($this->needCount == 1) {
			
			//look ( $sqltrans, "sqltrans" );
			$re = site_get_array ( $sqltrans, '', '', $cachetime );
			//look ( $re, 0, '-----re------' );
			$this->sum_row = $re [0] ['cnt'];
		} else {
			$this->sum_row = 1500;
		}
		//$this->sum_row = $db->num_rows (); //总记录数		
		

		self::get_sum_page ();
		
		//开始的行号为：
		$start_row = ($this->now_page - 1) * $this->num_everypage;
		
		//结束的行号为：
		$end_row = ($this->now_page) * $this->num_everypage;
		
		// 得到数组
		if ($start_row < 0) {
			$start_row = 0;
		}
		$sql = $sql . " limit " . $start_row . "," . $this->num_everypage;
		//look ( $sql, "sql--" );
		

		$this->result = site_get_array ( $sql, $keyid, $getfield, $cachetime );
		
		return $this->result;
	}
	
	//
	function getAnchor() {
		if ($this->anchor == '') {
			return;
		}
		return "#" . $this->anchor;
	}
	/**
	 * Enter description here...
	 *
	 */
	function get_sum_page() {
		//
		if (intval ( $_GET ['itemnum'] ) != 0) {
			$this->num_everypage = intval ( $_GET ['itemnum'] );
			$this->itemNumUrlAdd = ('&itemnum=' . $this->num_everypage);
		}
		
		if ($this->sum_row <= $this->num_everypage) {
			$this->sum_page = 1;
		} elseif (($this->sum_row) > ($this->num_everypage)) {
			if (($this->sum_row) % ($this->num_everypage) == 0) {
				$this->sum_page = ($this->sum_row) / ($this->num_everypage);
			} else {
				$page_tmp = ($this->sum_row - (($this->sum_row) % (($this->num_everypage)))) / $this->num_everypage;
				$this->sum_page = $page_tmp + 1;
			}
		}
		
		if ($this->now_page > $this->sum_page) {
			//$this->now_page = $this->sum_page;
		}
	}
	
	function get_simplelink($errormsg) {
		if ($this->sum_row == 0) {
			if ($this->errormsg != '') {
				$errormsg = $this->errormsg;
			}
			return $errormsg;
		}
		$content = "";
		
		if ($this->now_page > 1) {
			$content .= "<a class=\"prev\" href='" . $this->pagename . "&page=" . ($this->now_page - 1) . "{$this->itemNumUrlAdd}{$this->orderUrlAdd}'>上一页</a>";
		}
		
		if ($this->sum_page >= $this->now_page + 1) {
			$content .= "<a class=\"next\" href='" . $this->pagename . "&page=" . ($this->now_page + 1) . "{$this->itemNumUrlAdd}{$this->orderUrlAdd}'>下一页</a>";
		}
		
		return $content;
	}
	
	/**
	 * 输出下面的行连接显示
	 */
	function get_pagelink($errormsg = "还没有记录！", $nototal = 0, $noinput = 0) {
		//echo $this->errormsg;
		//
		if ($GLOBALS ['isbootstrap'] == 1) {
			
			return $this->get_pagelink_bootstrap ( $errormsg, $nototal = 0, $noinput = 0 );
		
		}
		
		if ($this->sum_row == 0) {
			if ($this->errormsg != '') {
				$errormsg = $this->errormsg;
			}
			return $errormsg;
		}
		$content .= ('<p class="page">');
		
		//
		if ($nototal == 0 and $this->haveTotal == 1) {
			$content .= ("<span> " . $this->sum_row . "条  &nbsp;&nbsp;/" . $this->sum_page . "页</span>");
		}
		
		if ($this->now_page > $this->pageBetween and $this->haveBeginEnd == 1) {
			//$content .= "<a href='" . $this->pagename . "&page=1{$this->itemNumUrlAdd}{$this->orderUrlAdd}'>首页</a>" . $this->page_link_space;
		}
		//$whichbetween = intval ( ($this->now_page - 1) / $this->pageBetween, 10 );
		

		if ($this->now_page != 1) {
			$content .= "<a class=\"prev\" href='" . $this->pagename . "&page=" . ($this->now_page - 1) . "{$this->itemNumUrlAdd}{$this->orderUrlAdd}{$this->getAnchor()}'>上页</a>" . $this->page_link_space;
		} else { //no-previous
		//$content .= "<a class=\"no-previous\" href='" . $this->pagename . "&page=" . ($this->now_page - 1) . "{$this->itemNumUrlAdd}{$this->orderUrlAdd}'><span>上页</span></a>" . $this->page_link_space;
		}
		//数字 显示10页
		//如果当前页
		

		if ($this->sum_page <= $this->pageBetween) {
			$countbetween = $this->sum_page;
			$beginPage = 1;
		} else if ($this->now_page + intval ( $this->pageBetween / 2 ) >= $this->sum_page) {
			//$countbetween = ($this->sum_page - $this->pageBetween * $whichbetween);
			$countbetween = $this->sum_page;
			$beginPage = $countbetween - $this->pageBetween + 1;
			if ($beginPage <= 0) {
				$beginPage = 1;
			}
			if ((($countbetween - $beginPage) - $this->pageBetween) < 0) {
				$countbetween += abs ( (($countbetween - $beginPage) - $this->pageBetween) ) - 1;
			}
		} else {
			$countbetween = $this->now_page + intval ( $this->pageBetween / 2 );
			$beginPage = $countbetween - $this->pageBetween + 1;
			if ($beginPage <= 0) {
				$beginPage = 1;
			}
			if ((($countbetween - $beginPage) - $this->pageBetween) < 0) {
				$countbetween += abs ( (($countbetween - $beginPage) - $this->pageBetween) ) - 1;
			}
		}
		
		//每一页
		for($count = $beginPage; $count <= $countbetween; $count ++) {
			if ($this->now_page == $count) {
				$class = "class=\"cur\"";
				$content .= ('<span class="cur">' . $count . '</span>');
			} else {
				$content .= "<a href='" . $this->pagename . "&page=" . $count . "{$this->itemNumUrlAdd}{$this->orderUrlAdd}{$this->getAnchor()}' " . $class . ">" . $count . $this->page_link_sign . "</a>" . $this->page_link_space;
			
			}
		
		}
		
		//
		if ($count < $this->sum_page) {
			$content .= ('<span>&nbsp;...</span>');
		}
		//下页
		if ($this->now_page != $this->sum_page) {
			$content .= "<a class=\"next\" href='" . $this->pagename . "&page=" . ($this->now_page + 1) . "{$this->itemNumUrlAdd}{$this->orderUrlAdd}{$this->getAnchor()}'>下页</a>" . $this->page_link_space;
		} else {
			//$content .= "<a class=\"no-next\" href='" . $this->pagename . "&page=" . ($this->now_page + 1) . "{$this->itemNumUrlAdd}{$this->orderUrlAdd}'><span>下页</span></a>" . $this->page_link_space;
		}
		
		if ($this->haveBeginEnd == 1) {
			//$content .= "<a href='" . $this->pagename . "&page=" . $this->sum_page . "{$this->itemNumUrlAdd}{$this->orderUrlAdd}'>尾页</a>";
		}
		
		if ($noinput == 0 and $this->itemSelect == 1 ) {
			$content .= "<span>每页显示 <select name=\"selectPageItemNum\" id=\"selectPageItemNum\" title=\"" . $this->pagename . (($_GET ['order'] != '') ? "&order=" . $_GET ['order'] : "") . "\" value=\"" . $this->num_everypage . "\">" . fm_get_select ( $this->itemNumList, $this->num_everypage, '', 0 ) . "</select>条</span>";
		}
		
		$content .= ('</p>');
		return $content;
	}
	
	/**
	 * 输出下面的行连接显示
	 */
	function get_pagelink_bootstrap($errormsg, $nototal = 0, $noinput = 0) {
		
		if ($this->sum_row == 0) {
			if ($this->errormsg != '') {
				$errormsg = $this->errormsg;
			}
			return $errormsg;
		}
		
		//数目
		

		//
		if ($nototal == 0 and $this->haveTotal == 1 and $GLOBALS ['role'] == "admin") {
			$content .= ("<div class=\"pull-right\"><div class=\"form-control-static\"> " . $this->sum_row . "条  &nbsp;&nbsp;/" . $this->sum_page . "页</div></div>");
		}
		
		//
		$content .= ('<nav><ul class="pagination">');
		
		if ($this->now_page != 1) {
			$lasturl = $this->pagename . "&page=" . ($this->now_page - 1) . $this->itemNumUrlAdd . $this->orderUrlAdd . $this->getAnchor ();
			$content .= ('<li><a href="' . $lasturl . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>');
		}
		//数字 显示10页
		//如果当前页
		

		if ($this->sum_page <= $this->pageBetween) {
			$countbetween = $this->sum_page;
			$beginPage = 1;
		} else if ($this->now_page + intval ( $this->pageBetween / 2 ) >= $this->sum_page) {
			//$countbetween = ($this->sum_page - $this->pageBetween * $whichbetween);
			$countbetween = $this->sum_page;
			$beginPage = $countbetween - $this->pageBetween + 1;
			if ($beginPage <= 0) {
				$beginPage = 1;
			}
			if ((($countbetween - $beginPage) - $this->pageBetween) < 0) {
				$countbetween += abs ( (($countbetween - $beginPage) - $this->pageBetween) ) - 1;
			}
		} else {
			$countbetween = $this->now_page + intval ( $this->pageBetween / 2 );
			$beginPage = $countbetween - $this->pageBetween + 1;
			if ($beginPage <= 0) {
				$beginPage = 1;
			}
			if ((($countbetween - $beginPage) - $this->pageBetween) < 0) {
				$countbetween += abs ( (($countbetween - $beginPage) - $this->pageBetween) ) - 1;
			}
		}
		
		//每一页
		for($count = $beginPage; $count <= $countbetween; $count ++) {
			if ($this->now_page == $count) {
				$class = "class=\"cur\"";
			
			}
			$nowurl = $this->pagename . "&page=" . $count . $this->itemNumUrlAdd . $this->orderUrlAdd . $this->getAnchor ();
			if ($count != $this->now_page) {
				$content .= ('<li><a href="' . $nowurl . '">' . $count . '</a></li>');
			} else {
				$content .= ('<li><a class="nowpage" href="' . $nowurl . '">' . $count . '</a></li>');
			}
		}
		
		//下页
		if ($this->now_page != $this->sum_page) {
			$nexturl = $this->pagename . "&page=" . ($this->now_page + 1) . $this->itemNumUrlAdd . $this->orderUrlAdd . $this->getAnchor ();
			$content .= ('<li><a href="' . $nexturl . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>');
		}
		
		$content .= ('</ul></nav>');
		
		if ($noinput == 0 and $this->itemSelect == 1 and $GLOBALS ['role'] == "admin") {
			$content = "<div class=\"pull-right mt20\"><select name=\"selectPageItemNum\" id=\"selectPageItemNum\" class=\"form-control selectpage\" title=\"" . $this->pagename . (($_GET ['order'] != '') ? "&order=" . $_GET ['order'] : "") . "\" value=\"" . $this->num_everypage . "\">" . fm_get_select ( $this->itemNumList, $this->num_everypage, '', 0 ) . "</select></div>" . $content;
		}
		
		return $content;
	}
	
	/**
	 * 
	 */
	function autojump($addUrl = "") {
		if ($_GET ['nojump'] == 1) {
			return;
		}
		
		if ($this->result == 0) {
			echo "已经没有记录";
			return;
		}
		
		//
		$url = $this->pagename . "&page=" . ($this->now_page + 1) . $this->itemNumUrlAdd . $this->orderUrlAdd . $this->getAnchor () . "&debug={$_GET['debug']}" . $addUrl;
		
		echo '<script>window.location.href="' . $url . '"; </script>';
	
	}
	
	/**
	 * 
	 * @param $addUrl
	 */
	function reload() {
		if ($this->result == 0) {
			echo "已经没有记录";
			return;
		}
		echo '<script>window.location.reload(); </script>';
	}
	
	##
	## 输出下面的行连接显示   jsonGoto(#page)
	##
	##
	function get_jsonpagelink($funModule) {
		if ($this->sum_row == 0)
			return "还没有记录！";
		$content = " <span >第 <font color=red>" . $this->now_page . "</font>页 /";
		$content .= "共<font color=red>" . $this->sum_row . "</font>条&nbsp;</span>";
		$content .= "<a href=\"javascript:" . str_replace ( '#page', '1', $funModule ) . "\"><span>首页</span></a>" . $this->page_link_space;
		
		$whichbetween = intval ( ($this->now_page - 1) / $this->pageBetween, 10 );
		
		//上十页
		

		if ($whichbetween != 0) {
			$content .= "<a href=\"javascript:" . str_replace ( '#page', (($whichbetween - 1) * $this->pageBetween + 1), $funModule ) . "\"><span>上10页</span></a>";
		} else {
			$content .= "";
		}
		
		//上页
		if ($this->now_page != 1) {
			$content .= "<a href=\"javascript:" . str_replace ( '#page', ($this->now_page - 1), $funModule ) . "\"><span>&lt;</scpan></a>" . $this->page_link_space;
		} else {
			$content .= "" . $this->page_link_space;
		
		}
		//数字 显示10页
		//如果当前页
		

		if ($this->sum_page <= $this->pageBetween) {
			$countbetween = $this->sum_page;
		} else if ($this->now_page + $this->pageBetween >= $this->sum_page) {
			$countbetween = ($this->sum_page - $this->pageBetween * $whichbetween);
		} else {
			$countbetween = $this->pageBetween;
		}
		
		for($count = $whichbetween * $this->pageBetween + 1; $count <= ($whichbetween) * $this->pageBetween + $countbetween; $count ++) {
			if ($this->now_page == $count) {
				$style = " class=pagelink ";
			}
			$content .= "<a href=\"javascript:" . str_replace ( '#page', $count, $funModule ) . "\" " . $style . ">" . $count . $this->page_link_sign . "</a>" . $this->page_link_space;
			$style = "";
		}
		
		//下页
		if ($this->now_page != $this->sum_page) {
			$content .= "<a href=\"javascript:" . str_replace ( '#page', ($this->now_page + 1), $funModule ) . "\">></a>" . $this->page_link_space;
		} else {
			$content .= ">" . $this->page_link_space;
		}
		
		//下十页
		if ($this->now_page + $this->pageBetween > $this->sum_page) {
			$content .= "下10页";
		} else {
			$content .= "<a href=\"javascript:" . str_replace ( '#page', (($whichbetween + 1) * $this->pageBetween + 1), $funModule ) . "\">下10页</a>";
		}
		
		$content .= "<a href=\"javascript:" . str_replace ( '#page', $this->sum_page, $funModule ) . "\">尾页</a>";
		$content .= "到<input name=\"thispage\" type=\"text\" id=\"thispage\" size=\"3\" style=\"height:18;width:20\">
						  页<input type=\"button\" name=\"Submit\" value=\"go\"   style=\"height:15px;width:31px;background-image: url('images/btn_ok.gif')\" onClick=\"gotoPage();\" class='PageGo'>
						<script language=\"JavaScript\">
						function gotoPage()
						{
						  var pagenum=document.getElementById('thispage').value;
						  if(pagenum!=null && pagenum!='')
						  {
						    if(!isNaN(pagenum))
						    {
						        this.location=\"javascript:" . str_replace ( '#page', 'pagenum', $funModule ) . "\";
						    }
						  }
						}</script>";
		return $content;
	}
	
	/**
	 * rewrite
	 */
	function get_pagelink_rewrite($v_msg = "还没有记录！", $nototal = 0, $noinput = 0) {
		if ($this->sum_row == 0)
			return $v_msg;
		if ($nototal == 0) {
			$content = " 第<b><font color=red>" . $this->now_page . "</font></b>页/";
			$content .= "共<b><font color=red>" . $this->sum_row . "</font></b>条&nbsp;";
		}
		$content .= "<a href=\"" . str_replace ( '#page', '1', $this->pagename ) . "\">首页</a>" . $this->page_link_space;
		
		$whichbetween = intval ( ($this->now_page - 1) / $this->pageBetween, 10 );
		
		//上十页
		

		if ($whichbetween != 0) {
			$content .= "<a href=\"" . str_replace ( '#page', (($whichbetween - 1) * $this->pageBetween + 1), $this->pagename ) . "\">上10页</a>";
		} else {
			$content .= "上10页";
		}
		
		//上页
		if ($this->now_page != 1) {
			$content .= "<a href=\"" . str_replace ( '#page', ($this->now_page - 1), $this->pagename ) . "\"><</a>" . $this->page_link_space;
		} else {
			$content .= "<" . $this->page_link_space;
		
		}
		//数字 显示10页
		//如果当前页
		

		if ($this->sum_page <= $this->pageBetween) {
			$countbetween = $this->sum_page;
		} else if ($this->now_page + $this->pageBetween >= $this->sum_page) {
			$countbetween = ($this->sum_page - $this->pageBetween * $whichbetween);
		} else {
			$countbetween = $this->pageBetween;
		}
		
		for($count = $whichbetween * $this->pageBetween + 1; $count <= ($whichbetween) * $this->pageBetween + $countbetween; $count ++) {
			if ($this->now_page == $count) {
				$style = " class=pagelink ";
			}
			$content .= "<a href=\"" . str_replace ( '#page', $count, $this->pagename ) . "\" " . $style . ">" . $count . $this->page_link_sign . "</a>" . $this->page_link_space;
			$style = "";
		}
		
		//下页
		if ($this->now_page != $this->sum_page) {
			$content .= "<a href=\"" . str_replace ( '#page', ($this->now_page + 1), $this->pagename ) . "\">></a>" . $this->page_link_space;
		} else {
			$content .= ">" . $this->page_link_space;
		}
		
		//下十页
		if ($this->now_page + $this->pageBetween > $this->sum_page) {
			$content .= "下10页";
		} else {
			$content .= "<a href=\"" . str_replace ( '#page', (($whichbetween + 1) * $this->pageBetween + 1), $this->pagename ) . "\">下10页</a>";
		}
		
		$content .= "<a href=\"" . str_replace ( '#page', $this->sum_page, $this->pagename ) . "\">尾页</a>";
		if ($noinput == 0) {
			$content .= "
						  到
						<input name=\"thispage\" type=\"text\" id=\"thispage\" size=\"3\" style=\"height:18;width:20\">
						  页
						  <input type=\"button\" name=\"Submit\" value=\"go\"   style=\"height:15px;width:31px;background-image: url('images/btn_ok.gif')\" onClick=\"gotoPage();\" class='PageGo'>
						
						
						<script language=\"JavaScript\">
						function gotoPage()
						{
						
						  var pagenum=document.getElementById('thispage').value;
						  if(pagenum!=null && pagenum!='')
						  {
						    if(!isNaN(pagenum))
						        {
						        this.location=\"" . str_replace ( '#page', 'pagenum', $this->pagename ) . "\";
						
						    }
						  }
						}
						</script>
						";
		}
		return $content;
	}
	
	##
	## 输出下面的行连接显示
	##
	##
	function print_pagelink() {
		print $this->get_pagelink ();
	}
	/**
	 * 
	 */
	function exportlink() {
		$nowurl = $this->pagename . "&export=1&page=" . $this->now_page . $this->itemNumUrlAdd . $this->orderUrlAdd . $this->getAnchor ();
		return '<a href="' . $nowurl . '">导出本页数据</a>';
	}

}