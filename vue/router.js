import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/home'
import OauthCallback from './views/oauthCallback'
import Publish from './views/publish'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'index',
      component: Home,
      // children: indexChildren
    },
    {
      path: '/oauth/callback',
      name: 'oauthCallback',
      component: OauthCallback,
    },
    {
      path: '/publish',
      name: 'publish',
      component:  Publish
    }
    // {
    //   path: '',
    //   redirect: '/'
    // },
    // {
    //   path: '*',
    //   redirect: '/'
    // },
  ]
})
