<?php

class User extends Module
{

    public $id;
    public $username;
    public $password;
    public $realname;
    public $email;


    /**
     * 聚才林验证入库操作
     */
    function validate()
    {
        $errors = array();

        if ($this->realname == '') {
            $errors['realname']="姓名不能为空";
        }

        if (($this->username) == '') {
            $errors['username']="用户名不能为空";
        }

        if (($this->password) == '') {
            $errors['password']="密码不能为空";
        }


        if (count($errors) > 0) {
            $exp = new LcError ("录入错误");
            $exp->errors = $errors;
            throw $exp;
        }
    }

    /**
     * 聚才林插入数据库操作
     * @param AstepDay $astepDay 聚才林实体
     * @return AstepDay
     */

    static function save(User $user)
    {
        //基本类信息验证
        $user->validate();

        //验证程序代码段


        //是否是只做验证
        if ($isvalide == 1) {
            return true;
        }
        if (intval($user->id) != 0) {


            //数据更新
            $sql = "update user set
			
			username='" . ($user->username) . "',
			password='" . ($user->password) . "',
			email='" . ($user->email) . "',
			realname='" . ($user->realname) . "'

			where id='{$user->id}' limit 1";
            if (!site_data_update($sql)) {
                throw new LcError ("更新聚才林数据失败");
            }

            //tocache
            //$astepDay->setPrimaryKey ( "id" );
            //$astepDay->toCache ();
            return $user;


        }


//看是否存在
        $existUsers = self::find(array('username' => $user->username), 1);
        look($existUsers);
        if (count($existUsers) > 0) {
            throw new LcError("这个用户{$user->username} 已经存在");
        }

        //数据添加
        $user->createtime = $user->createdate = date("Y-m-d H:i:s");
        $sql = "insert into user set 
			username='" . ($user->username) . "',
			password='" . ($user->password) . "',
			email='" . ($user->email) . "',
			realname='" . ($user->realname) . "'";

        $user->id = site_data_insert($sql, 'increment');
        if ($user->id == 0) {
            throw new LcError ("插入聚才林错误");
        }
        //tocache
        //$astepDay->setPrimaryKey ( "id" );
        //$astepDay->toCache ();
        return $user;
    }


    /**
     * 查找某条信息
     *
     * @param  id
     * @return AstepDay
     */
    static function getById($id)
    {
        $id = intval($id);
        if ($id == 0) {
            throw new LcError ("还没有输入聚才林 ( AstepDay )编号");
        }

        /* 是否开启缓存
        $re = self::getCache ( $id );
        if ($re != null) {
            return $re;
        }
        */

        $sql = "select a.* from user a where a.id='{$id}'  limit 1";
        $result = site_get_array($sql);


        if (count($result) == 0) {
            return null;
        }


        $user = new User ($result [0]);


        /* 是否开启缓存
        $astepDay->setPrimaryKey ( "id" );
        $astepDay->toCache ();
        */

        return $user;
    }


    /**
     * 查找某条信息
     *
     * @param  id
     * @return AstepDay
     */
    static function getByUsername($username)
    {
        $username = trim($username);
        if ($username == '') {
            throw new LcError ("还没有输入聚才林 ( AstepDay )编号");
        }

        /* 是否开启缓存
        $re = self::getCache ( $id );
        if ($re != null) {
            return $re;
        }
        */

        $sql = "select a.* from user a where a.username='{$username}'  limit 1";
        $result = site_get_array($sql);


        if (count($result) == 0) {
            return null;
        }


        $user = new User ($result [0]);


        /* 是否开启缓存
        $astepDay->setPrimaryKey ( "id" );
        $astepDay->toCache ();
        */

        return $user;
    }

    /**
     * 聚才林查找操作
     *
     * @param array $c 条件
     * @param  $page 页面
     * @param  $para 参数
     * @param  $order  排序
     * @return Page
     */
    static function find(array $c, $page = 1, $para = "a.*", $order = "id desc")
    {


        //组装条件

        $carray ['source'] = " and a.source = '" . $c ['source'] . "'";
        $carray['username'] = " and a.username = '" . $c['username'] . "'";
        $carray['email'] = " and a.email = '" . $c['email'] . "'";


        //允许的排序
        $oarray ['id'] = "a.id";
        $oarray ['uid'] = "a.uid";
        $oarray ['project_id'] = "a.project_id";
        $oarray ['recorddate'] = "a.recorddate";
        $oarray ['title'] = "a.title";
        $oarray ['content'] = "a.content";
        $oarray ['pics'] = "a.pics";
        $oarray ['isplan'] = "a.isplan";
        $oarray ['status'] = "a.status";
        $oarray ['createtime'] = "a.createtime";

        //获得排序Sql
        $orderSql = site_order_sql($oarray, $order);

        /*
        //显示其他表数据
		if($c['showField']==1){
		    $para.=",t.field";
			$leftJoinSql.=" LEFT JOIN table t ON a.id=t.id ";
		}
		*/


        //group by
        $groupBySql = "";
        if ($c['groupBy'] != '') {
            $groupBySql = " GROUP BY {$c[groupBy]} ";
            $orderSql = "";
        }


        $whereSql = site_condition_sql($c, $carray);

        //组装sql语句
        $sql = "select {$para} from user a  {$leftJoinSql} where 1 {$whereSql} {$groupBySql} {$orderSql}";

        //分页的情形
        if (get_class($page) == "Page") {
            $page->lc_page_array($sql, $_GET ['page'], $c ['key'], $c ['value'], $c['cache']);
            $page->setPageAdd($c, $carray);
            return $page;
        }
        //直接返回
        if (intval($page) != 0) {
            $limit = " limit 0," . intval($page);
        }

        $result = site_get_array($sql . $limit, $c ['key'], $c ['value'], $c['cache']);
        return $result;
    }


}


