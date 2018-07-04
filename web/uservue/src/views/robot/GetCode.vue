<template>
  <section>

    <el-form :inline="true" class="demo-form-inline" size="small">
      <el-form-item label="选择数据">
        <el-select v-model="getcode.data_id" placeholder="数据" class="widthmin" clearable @change="getCode">
          <el-option v-for="item in datas" :key="item.id" :label="item.title" :value="item.id">
          </el-option>
        </el-select>
        <el-button type="primary" plain @click="showData()">编辑</el-button>
      </el-form-item>

      <el-form-item label="选择模板">
        <el-select v-model="getcode.tpl_id" placeholder="模板" class="widthmin" clearable @change="getCode">
          <el-option v-for="item in tpls" :key="item.id" :label="item.title" :value="item.id">
          </el-option>
        </el-select>
        <el-button type="primary" plain @click="showTpl()">编辑</el-button>
      </el-form-item>

      <el-form-item label="获得Code">
        <el-button type="primary" @click="getCode()">确定</el-button>
      </el-form-item>
    </el-form>

    <div>
      <codemirror v-model="getcode.code" :options="editoroptions.getcode"></codemirror>
      <!-- <el-input v-model="getcode.code" type="textarea" class="code" row="60" style="height:700px;"></el-input>-->
    </div>

    <!--tpl-->
    <el-dialog title="数据保存" :visible.sync="show.data" fullscreen>
      <el-row v-loading="show.loading">
        <el-col :span="24">
          <el-form :model="form.data" label-width="100px" ref="formData">
            <el-form-item label="标题">
              <el-input v-model="form.data.title"></el-input>
            </el-form-item>
            <el-form-item label="标题">
              <el-button @click.native="show.db = true">获得数据库数据</el-button>
              <el-button @click.native="datainit">初始化数据</el-button>
            </el-form-item>
            <el-form-item label="内容">
              <codemirror v-model="form.data.datacontent"></codemirror>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <div slot="footer" class="dialog-footer">
        <el-button @click.native="show.data = false">取消</el-button>

        <el-button type="primary" @click="saveData">提交</el-button>
      </div>
    </el-dialog>
    <!--/tpl-->

    <!--tpl-->
    <el-dialog title="模板保存" :visible.sync="show.tpl" fullscreen>
      <el-row v-loading="show.loading">
        <el-col :span="24">
          <el-form :model="form.tpl" label-width="100px" ref="formData">
            <el-form-item label="标题">
              <el-input v-model="form.tpl.title"></el-input>
            </el-form-item>
            <el-form-item label="类型">
              <el-input v-model="form.tpl.codetype"></el-input>
            </el-form-item>
            <el-form-item label="内容">
              <codemirror v-model="form.tpl.tplcontent" :options="editoroptions.tpl"></codemirror>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <div slot="footer" class="dialog-footer">
        <el-button @click.native="show.tpl = false">取消</el-button>
        <el-button type="primary" @click="saveTpl">提交</el-button>
      </div>
    </el-dialog>
    <!--/tpl-->

    <!--tpl-->
    <el-dialog title="数据库设置" :visible.sync="show.db">
      <el-row v-loading="show.loading">
        <el-col :span="24">
          <el-form :model="form.db" label-width="100px" ref="formData">
            <el-form-item label="Host主机">
              <el-input v-model="form.db.host"></el-input>
            </el-form-item>
            <el-form-item label="用户名">
              <el-input v-model="form.db.username"></el-input>
            </el-form-item>
            <el-form-item label="密码">
              <el-input v-model="form.db.password"></el-input>
            </el-form-item>
            <el-form-item label="数据库">
              <el-input v-model="form.db.db"></el-input>
            </el-form-item>
            <el-form-item label="表名">
              <el-input v-model="form.db.table"></el-input>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>
      <div slot="footer" class="dialog-footer">
        <el-button @click.native="show.db = false">取消</el-button>
        <el-button type="primary" @click="getDBFields()">提交</el-button>
      </div>
    </el-dialog>
    <!--/tpl-->

  </section>
</template>

<script>
import {
  codeTplFindApi,
  codeDataFindApi,
  codeTplSaveApi,
  codeDataSaveApi,
  codeGetCodeApi,
  codeGetDBFieldsApi
} from "@/api/api";

import Vue from "vue";

var VueCodeMirror = require("vue-codemirror-lite");
Vue.use(VueCodeMirror);

require("codemirror/mode/javascript/javascript");
require("codemirror/mode/vue/vue");
require("codemirror/mode/php/php");

export default {
  data() {
    return {
      editoroptions: {
        tpl: {
          extraKeys: { "Ctrl-Space": "autocomplete" }
        },
        getcode: {
          extraKeys: { "Ctrl-Space": "autocomplete" }
        }
      },
      tpls: [{ id: "0", title: "添加" }],
      datas: [],
      getcode: {
        tpl_id: 0,
        data_id: 0,
        code: ""
      },
      show: {
        tpl: false,
        data: false,
        db: false
      },
      form: {
        tpl: {},
        data: {},
        db: {}
      }
    };
  },
  watch: {
    form: {
      handler(val) {
        this.editoroptions.tpl.mode = this.form.tpl.codetype;
      },
      deep: true
    }
  },
  methods: {
    getTpls: function() {
      let para = {};
      codeTplFindApi(para).then(res => {
        this.tpls = res.data.crCodeTpls;
        this.tpls[0] = { id: 0, title: "添加" };
        this.look(this.tpls);
      });
    },
    getDatas: function() {
      let para = {};
      codeDataFindApi(para).then(res => {
        this.datas = res.data.crCodeDatas;
        this.datas[0] = { id: 0, title: "添加" };
        this.look(this.datas);
      });
    },
    getCode: function() {
      this.look("change" + this.getcode.tpl_id + "|" + this.getcode.data_id);
      if (this.getcode.tpl_id == 0 || this.getcode.data_id == 0) {
        return;
      }
      //
      codeGetCodeApi(this.getcode.tpl_id, this.getcode.data_id).then(res => {
        this.getcode.code = res.data.code;
      });
    },
    getDBFields: function() {
      sessionStorage.setItem("db", JSON.stringify(this.form.db));
      //
      codeGetDBFieldsApi(this.form.db).then(res => {
        this.look(res);
        //this.getcode.code = res.data.code;
        this.form.data.datacontent = JSON.stringify(
          res.data.result,
          null,
          "\t"
        );
      });
    },
    showTpl: function() {
      this.editoroptions.tpl.mode = this.form.tpl.codetype;
      this.look("---tpl_id: " + this.getcode.tpl_id + "--");
      if (this.getcode.tpl_id == 0) {
        this.look("---no --");
        this.form.tpl = {};
      } else {
        this.form.tpl = this.tpls[this.getcode.tpl_id];
      }

      //
      this.show.tpl = true;
      this.look("----tpl----");
      this.look(this.form.tpl);
    },
    saveTpl: function() {
      codeTplSaveApi(this.form.tpl).then(res => {
        if (!res.data.type == "success") {
          alert(res.data.message);
          return;
        }
        //
        this.$message({
          message: "提交成功",
          type: "success"
        });
        //
        this.show.tpl = false;
        this.getTpls();
      });
    },
    showData: function() {
      this.look(this.tpls);
      this.look(this.getcode.tpl_id);

      if (this.getcode.data_id == 0) {
        this.look("---no --");
        this.form.data = {};
      } else {
        this.form.data = this.datas[this.getcode.data_id];
      }
      //
      this.show.data = true;
      this.look("----tpl----");
      this.look(this.form.data);
    },
    saveData: function() {
      codeDataSaveApi(this.form.data).then(res => {
        if (!res.data.type == "success") {
          alert(res.data.message);
          return;
        }
        //
        this.$message({
          message: "提交成功",
          type: "success"
        });
        //
        this.show.data = false;
        this.getDatas();
      });
    },
    datainit: function() {
      var data = {
        table: "obj",
        object: "obj",
        Object: "Obj",
        tablecomment: "表名",
        fields: [
          {
            comment: "编号",
            type: "int",
            name: "id",
            showtype: "int",
          },
          {
            comment: "字段",
            type: "varchar",
            name: "fields",
            showtype: "text"
          }
        ]
      };

      this.look(data);
      this.form.data.datacontent = JSON.stringify(data, null, "\t");
    }
  },

  created() {
    this.look("----");
    this.getTpls();
    this.getDatas();

    let db = JSON.parse(sessionStorage.getItem("db"));
    if (db) {
      this.form.db = db;
    }
  }
};
</script>

<style>
.code textarea {
  width: 100%;
  height: 400px;
}
.el-form-item__content {
  line-height: 14px;
}
.CodeMirror {
  height: 500px;
}
.vue-codemirror-wrap {
  border: 1px solid gray;
}
</style>
