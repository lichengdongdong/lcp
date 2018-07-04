<?php

class {$data.Object}Controller
{

    /**
     * 
     */
    public function find()
    {

        $re = $GLOBALS['resume'];
        $farray = $_GET;

        ${$data.object}Page = {$data.Object}::find($farray, new Page());

        //
        unset($farray['status']);
        $farray['groupBy'] = 'status';
        $farray['key'] = 'status';
        $farray['value'] = 'cnt';

        $cnt = {$data.Object}::find($farray, 10, $para = "status,count(0) as cnt", $order = "id asc");

        $s = new LcSuccess("执行成功");
        $s->{$data.object}s = ${$data.object}Page->result;
        $s->total = intval(${$data.object}Page->sum_row);
        $s->cnt = $cnt;
        $s->show();
    }

    public function get()
    {

        ${$data.object} = {$data.Object}::getById($_GET['id']);

        $s = new LcSuccess("执行成功");
        $s->{$data.object} = ${$data.object};
        $s->show();

    }

    public function save()
    {

        $id = intval($_REQUEST['id']);
        ${$data.object} = ($id == 0) ? new {$data.Object}() : {$data.Object}::getById($id);

        //更新
        ${$data.object}->update($_POST);
        ${$data.object} = {$data.Object}::save(${$data.object});

        //显示
        $s = new LcSuccess("执行成功");
        $s->{$data.object} = ${$data.object};
        $s->show();

    }

    public function delete()
    {

    }

}
<?php

class {$data.Object}Controller
{
    /**
    *  职位列表信息
     */
    public function find()
    {

      
        $farray = $_GET;
        ${$data.object}Page = {$data.Object}::find($farray, new Page());


        $s = new LcSuccess("执行成功");
        $s->{$data.object}s = ${$data.object}Page->result;
        $s->total = intval(${$data.object}Page->sum_row);
        $s->show();
    }
/**
 * 获得单个信息
 */
    public function get()
    {

        ${$data.object} = {$data.Object}::getById($_GET['id']);

        $s = new LcSuccess("执行成功");
        $s->{$data.object} = ${$data.object};
        $s->show();

    }

    
    public function save()
    {

        $id = intval($_REQUEST['id']);
        ${$data.object} = ($id == 0) ? new {$data.Object}() : {$data.Object}::getById($id);

        //更新
        ${$data.object}->update($_POST);
        ${$data.object} = {$data.Object}::save(${$data.object});

        //显示
        $s = new LcSuccess("执行成功");
        $s->{$data.object} = ${$data.object};
        $s->show();

    }

    public function delete()
    {

    }
}
