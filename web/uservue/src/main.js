// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './routers'

Vue.config.productionTip = false


//mint
import 'mint-ui/lib/style.css'
import Mint from 'mint-ui';
Vue.use(Mint);

//
import util from '@/common/util'
import global from '@/globals'


//look
Vue.prototype.look = function (vars) {
  console.log(vars);
}
Vue.prototype.getDomain = function () {
  return global.domain;
}

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
