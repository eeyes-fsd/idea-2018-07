import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'index',
      // component: Index,
      // children: indexChildren
    },
    {
      path: '',
      redirect: '/'
    },
    {
      path: '*',
      redirect: '/'
    },
  ]
})
