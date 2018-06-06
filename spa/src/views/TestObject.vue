
<template>

    <div id="appa">
        <!--list-->
        <div class="lists">
            <div v-for="object in objects">
                {{object.realname}} | {{object.sex}}
                <button type="button" @click="currentObjectId=object.id;showObject()">显示</button>
                <button type="button" @click="currentObjectId=object.id;submitObject(0)">修改</button>
            </div>
        </div>
        <!--/list-->

        <!--detail-->
        <div class="detail" id="ObjectDetail" v-if="isShowObject==1">
            <button @click="isShowObject=0">x</button>
            {{object.realname}} | {{object.sex}}
            <button @click="submitObject(0)">编辑</button>
        </div>
        <!--/detail-->

        <!--form-->
        <form class="form" v-if="isShowObjectForm==1">
            <button type="button" @click="isShowObjectForm=0">x</button>
            <input v-model="object.realname" />
            <input v-model="object.sex" />
            <button type="button" @click="submitObject(1)">save</button>
        </form>
        <!--/form-->

    </div>

</template>



<script>
import config from "@/common/config";
import util from "@/common/util";
import axios from "axios";
import qs from "qs";
axios.defaults.headers.post["Content-Type"] =
  "application/x-www-form-urlencoded; charset=UTF-8";

export default {
  data() {
    return {
      config: config, //初始化配置文件
      objects: [], //对象列表
      
      //
      currentObjectId: 0,  //当前的对象编号
      object: {}, //单个对象

      /*展示*/
      isShowObject: 0, //是否显示
      isShowObjectForm: 0
    };
  },
  methods: {
    /*增删改查*/
    //查询对象
    findObject: function() {
      var that = this;
      //获得远程
      axios.get("http://localhost/api/resume", {}).then(function(re) {
        that.objects = re.data.resumes;
      });
    },
    //得到一个对象
    showObject: function() {
      var that = this;
      this.isShowObject = 1;

      //获得远程
      axios
        .get("http://localhost/api/resume/" + this.currentObjectId, {})
        .then(function(re) {
          that.object = re.data.resume;
          that.isShowObject = 1;
        });
      return;
    },
    //保存对象
    submitObject: function(click) {
      var that = this;

      //如果是显示
      if (click != 1) {
        //获得远程
        axios
          .get("http://localhost/api/resume/" + this.currentObjectId, {})
          .then(function(re) {
            that.object = re.data.resume;
            that.isShowObjectForm = 1;
            that.isShowObject = 0;
          });
        return;
      }

      //直接提交
      var paras = Object.assign({}, this.object);
      console.log(paras);
      axios
        .post(
          "http://localhost/api/resume/" + this.currentObjectId,
          qs.stringify(paras)
        )
        .then(function(re) {
          that.object = re.data.resume;
          that.isShowObjectForm = that.isShowObject = 0;
          that.findObject();
        });
      return;
    },
    //删除对象
    deleteObject: function() {}
  },
  //创建后
  created() {
    //
    this.findObject();
  }
};
</script>



<style>
.detail,.form {
  border: 1px silver solid;
  padding: 10px;
}
</style>