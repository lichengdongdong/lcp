<?php
/* Smarty version 3.1.32, created on 2018-07-04 14:23:44
  from '2b6baa0376c575f1925811417d562962a5773029' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5b3c67f05e4540_99045923',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b3c67f05e4540_99045923 (Smarty_Internal_Template $_smarty_tpl) {
?><template>
    <div id="detail">
        <el-dialog :title="name+'显示'" :visible.sync="show.dialog"  width="70%">
            <el-row>
                <el-col :span="24">
                    <el-form :model="detail" label-width="100px" ref="formData">
                        <div class="">
                          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['fields'], 'f');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['f']->value) {
?>
                            <el-form-item label="<?php echo $_smarty_tpl->tpl_vars['f']->value['comment'];?>
">
                                {{ detail.<?php echo $_smarty_tpl->tpl_vars['f']->value['name'];?>
 }}
                            </el-form-item>
                          <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            
                      
                        </div>
                    </el-form>
                </el-col>
            </el-row>
        </el-dialog>
    </div>
</template>

<?php echo '<script'; ?>
>
import Vue from "vue";
import util from "@/common/js/util";
import config from "@/common/js/config";
import { <?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
GetApi } from "@/api/api";
  
//export const <?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
GetApi = params => { return axios.get(`base/<?php echo $_smarty_tpl->tpl_vars['data']->value['object'];?>
/get`, { params: params }); };

export default {
  data() {
    return {
      name: "<?php echo $_smarty_tpl->tpl_vars['data']->value['tablecomment'];?>
",
      config: config,
      detail: {
        realname: ""
      },
      show: {
        dialog: false
      }
    };
  },
  props: ["id", "value"],
  created() {
    if (this.id > 0) {
      this.show.dialog = true;
    }
    this.getDetail();
  },
  watch: {
    value: function(ne, ol) {
      this.show.dialog = this.value;
      if (ne == true) {
        this.getDetail();
      }
    },
    show: {
      handler(val) {
        this.look(this.show);
        this.$emit("input", this.show.dialog);
      },
      deep: true
    },
    id: function(n) {
      this.look("lll" + n);
      this.look(this.show.dialog);
      if (this.show.dialog == true) {
        this.getDetail();
      }
    }
  },
  methods: {
    getDetail: function() {
      if (this.id == 0) {
        return;
      }
      this.detail = {};
      this.show.loading = true;
      jobGetApi(this.id).then(res => {
        this.look(res);
        this.detail = res.data.job;
        this.show.loading = false;
      });
    }
  }
};
<?php echo '</script'; ?>
>


<style lang="scss">
</style><?php }
}
