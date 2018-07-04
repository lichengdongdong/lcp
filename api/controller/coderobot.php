<?php

//include "entity/crCodeTpl.php";

class CoderobotController
{

    public function getcode()
    {
        $tpl_id = intval($_GET['tpl_id']);
        $data_id = intval($_GET['data_id']);

        if ($tpl_id == 0 or $data_id == 0) {
            throw new LcError("不能为0");
        }

        $crCodeTpl = CRCodeTpl::getById($tpl_id);
        $crCodeData = CRCodeData::getById($data_id);

        $data = $crCodeData->getData();

        $tpl = $crCodeTpl->tplcontent;
        //$tpl='xx{$data}dad';

        //
        require 'lcp/smarty/Smarty.class.php';
        $smarty = new Smarty;
        $smarty->assign("data", $data);
        $code = $smarty->fetch("string:" . $tpl);

        //
        $s = new LcSuccess("ok");
        $s->code = $code;
        $s->tpl = $crCodeTpl;
        $s->data = $crCodeData;

        $s->show();
    }

    /**
     */
    public function tplfind()
    {

        $crCodeTplPage = CRCodeTpl::find(array('key' => 'id'), new Page());

        $s = new LcSuccess("执行成功");
        $s->crCodeTpls = $crCodeTplPage->result;
        $s->total = intval($crCodeTplPage->sum_row);
        $s->show();
    }

    public function tplget()
    {

        $crCodeTpl = CRCodeTpl::getById($_GET['id']);
//
        $s = new LcSuccess("执行成功");
        $s->crCodeTpl = $crCodeTpl;
        $s->show();

    }

    public function tplsave()
    {

        $id = intval($_REQUEST['id']);
        $crCodeTpl = ($id == 0) ? new CRCodeTpl() : CRCodeTpl::getById($id);

        //
        $crCodeTpl->update($_POST);
        $crCodeTpl = CRCodeTpl::save($crCodeTpl);

        //显示
        $s = new LcSuccess("执行成功");
        $s->crCodeTpl = $crCodeTpl;
        $s->show();

    }

    /**
     */
    public function datafind()
    {
        $crCodeDataPage = CRCodeData::find(array('key' => 'id'), new Page());

        $s = new LcSuccess("执行成功");
        $s->crCodeDatas = $crCodeDataPage->result;
        $s->total = intval($crCodeDataPage->sum_row);
        $s->show();
    }

    public function dataget()
    {
        $crCodeData = CRCodeData::getById($_GET['id']);
//
        $s = new LcSuccess("执行成功");
        $s->crCodeData = $crCodeData;
        $s->show();
    }

    public function datasave()
    {

        $id = intval($_REQUEST['id']);
        $crCodeData = ($id == 0) ? new CRCodeData() : CRCodeData::getById($id);

        //
        $crCodeData->update($_POST);
        $crCodeData = CRCodeData::save($crCodeData);

        //显示
        $s = new LcSuccess("执行成功");
        $s->crCodeData = $crCodeData;
        $s->show();

    }

    public function getdbfields()
    {

        try {
            $dsn = "mysql:host=" . $_REQUEST['host'] . ";dbname=" . $_REQUEST['db'];
            $array = array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8");
            $pdo = new PDO($dsn, $_REQUEST['username'], $_REQUEST['password'], $array);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            //
            $sql = "SELECT COLUMN_NAME, DATA_TYPE, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_name = '{$_REQUEST['table']}' and table_schema ='" . $_REQUEST['db'] . "'";
            $stmt = $pdo->query($sql);
            $res = $stmt->fetchAll();

            //
            $sql2 = "SELECT TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES  WHERE TABLE_NAME = '{$_REQUEST['table']}' AND TABLE_SCHEMA = '{$_REQUEST['db']}'";
            $stmt2 = $pdo->query($sql2);
            $res2 = $stmt2->fetchAll();

        } catch (PDOException $e) {

            throw new LcError($e->getMessage());
        }

        $return = new Module();
        $return->table = $_REQUEST['table'];
        $return->object = $_REQUEST['table'];
        $return->Object = ucwords($_REQUEST['table']);
        $return->tablecomment = $res2[0]['TABLE_COMMENT'];

        $fields = array();
        foreach ($res as $f) {
            $ff['comment'] = $f['COLUMN_COMMENT'];
            $type = explode("|", $f['DATA_TYPE']);

            //
            $ff['type'] = $type[0];
            $ff['showtype'] = coderobot_showtype($type[0], $type[1]);
            $ff['name'] = $f['COLUMN_NAME'];
            $ff['required'] = "0";
            $fields[] = $ff;
        }
        $return->fields = $fields;

        $s = new LcSuccess("ok");
        $s->result = $return;
        $s->show();

    }

}

function coderobot_showtype($type, $typedesc)
{
    $showtype = "showtype";
    switch ($type) {
        case 'text':
            $showtype = "textarea";
            break;
        case 'date':
            $showtype = "date";
            break;
        case 'tinyint':
            $showtype = "select";
            break;
        case 'varchar':
            $showtype = "text";
        default:
            # code...
            break;
    }
    return $showtype;
}
