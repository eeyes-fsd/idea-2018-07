import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/home'
import Search from './views/home/Search'
import OauthCallback from './views/oauthCallback'
import Publish from './views/publish'
import Article from './views/article'
import User from './views/user/index'
import userArticle from './views/user/userArticle'
import userMessage from './views/user/userMessage'
import userFavourite from './views/user/userFavourite'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'index',
      component: Home,
      children:[
        {
          path: '/search',
          name: 'search',
          component: Search,
        }
      ]
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
    },
    {
      path: '/user',
      name: 'user',
      component: User,
      children:[
        {
          path: '/article',
          component: userArticle
        },
        {
          path: '/message',
          component: userMessage
        },
        {
          path: '/favourite',
          component: userFavourite
        }
      ]
    },
    {
      path: '/article/:id',
      name: 'article',
      component: Article
    }
  ]
})
