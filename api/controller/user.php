<?php

class UserController
{

    /**
     *
     */
    public function test()
    {
        look("-----");
        $u = array('realname' => "licheng");
        $user = new User($u);
        look($user);

        $s = new LcSuccess("ok");
        $s->user = $user;
        $s->show();
    }

    /**
     */
    public function find()
    {

        $re = $GLOBALS['resume'];

        $userPage = User::find($_GET, new Page());

        $s = new LcSuccess("执行成功");
        $s->users = $userPage->result;
        $s->total = intval($userPage->sum_row);
        $s->show();
    }

    /**
     *
     */
    public function setotherjobrela()
    {

        $other_jids = explode(",", $_POST['other_jids']);
        $user_email = $_POST['user_email'];
//
        if (count($other_jids) == 0) {
            throw new LcError("还没有传递jid");
        }

        //
        $users = User::find(array('email' => $user_email), 1);
        if (count($users) == 0) {
            throw new LcError("没有这个邮箱对应的用户");
        }

        $user = new User($users[0]);
        look($user);


        //
        foreach ($other_jids as $jid) {

            $userOtherjob = new UserOtherJob();
            $userOtherjob->other_jid = $jid;
            $userOtherjob->user_id = $user->id;
            UserOtherJob::save($userOtherjob);

        }


        $s = new LcSuccess("执行成功");

        $s->show();
    }

    /**
     * 用户登陆
     */
    public function login()
    {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if ($username == '' or $password == '') {
            throw new LcError("用户名和密码不能为空");
        }
//
        $users = User::find(array('username' => $username), 1);
        if (count($users) == 0) {
            throw new LcError("没有这个用户");
        }
        look($users);
        //
        $user = new User($users[0]);
        look($user);
        look("---");
        look($password . "|" . $user->password);
        if ($user->password != $password) {
            throw new LcError("密码不对");
        }

        //
        $s = new LcSuccess("成功");
        $s->user = $user;
        $s->show();

    }

    public function get()
    {

        $s = new LcSuccess("执行成功");
        $s->user = User::getById($_GET['id']);
        $s->show();

    }

    public function save()
    {

        $id = intval($_REQUEST['id']);
        $user = ($id == 0) ? new User() : User::getById($id);


        //更新
        $user->update($_POST);
        if ($id != 0 and $_POST['new_password'] != '') {
            $user->password = $_POST['new_password'];
        }


        //
        $user = User::save($user);

        //显示
        $s = new LcSuccess("执行成功");
        $s->user = $user;
        $s->show();

    }

    public function delete()
    {

    }

}
