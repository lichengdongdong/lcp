import Vue from 'vue'
import Router from 'vue-router'
import TestObject from '@/views/TestObject'
//import HelloWorld from '@/components/HelloWorld'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/test',
      name: 'TestObject',
      component: TestObject
    }
  ]
})
