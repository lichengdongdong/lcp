<?php
/* Smarty version 3.1.32, created on 2018-07-04 17:29:27
  from '5ecbcac1641626efabea9fe346778070a20fff0f' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5b3c93770044b0_60420247',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b3c93770044b0_60420247 (Smarty_Internal_Template $_smarty_tpl) {
echo '<?php

';?>class <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
Controller
{

    /**
     * 返回列表
     */
    public function find()
    {

       
        $farray = $_GET;

        $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
Page = <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
::find($farray, new Page());

        
        $s = new LcSuccess("执行成功");
        $s-><?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
s = $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
Page->result;
        $s->total = intval($<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
Page->sum_row);
        $s->show();
    }

  
  
  /**
   *
   */
    public function get()
    {

        $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
 = <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
::getById($_GET['id']);

        $s = new LcSuccess("执行成功");
        $s-><?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
 = $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
;
        $s->show();

    }

  
  /*
  */
    public function save()
    {

        $id = intval($_REQUEST['id']);
        $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
 = ($id == 0) ? new <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
() : <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
::getById($id);

        //更新
        $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
->update($_POST);
        $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
 = <?php echo $_smarty_tpl->tpl_vars['data']->value['Object'];?>
::save($<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
);

        //显示
        $s = new LcSuccess("执行成功");
        $s-><?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
 = $<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
;
        $s->show();

    }

  
  /*
   *
   */
    public function delete()
    {

    }

}<?php }
}
