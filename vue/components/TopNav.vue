<template>
  <nav class="top-nav flex-row">
    <div class="logo">
      <img src="" alt="logo" class="logo">
    </div>
    <ul class="links flex-row">
      <li>
        <router-link to="/">首页</router-link>
        <router-link to="/">干货</router-link>
        <router-link to="/">项目</router-link>
        <router-link to="/">资源</router-link>
        <router-link to="/">奇思妙想</router-link>
        <router-link to="/">其他</router-link>
      </li>
    </ul>
    <div class="welcome" v-if="!ifLogin">
      <p>欢迎访问创意工坊，请先<a href="javascript:;" @click="userLogin">登录</a></p>
    </div>
    <div class="actions" v-else>
      <router-link to="/article">个人中心</router-link>
      <router-link to="/publish">发表文章</router-link>
    </div>
  </nav>
</template>

<script>
import { mapState } from 'vuex'
import requests, { getLoginType, setAccessToken } from '@/api/requests.js'
import { getCookie, delCookie } from '../util'

export default {
  name: 'TopNav',
  data() {
    return {
      name: '',
      ifLogin: false,
      org: {
        email: '',
        password: ''
      }
    }
  },
  methods: {
    async userLogin () {
      //个人登录
      try {
        let data = await requests.get('/users/authorizations')
        location.href = data.url
      } catch (err) {
        this.errorMessage = err.message || '未知错误'
      }
    },
    async orgLogin () {
      //社团登录
      try {
        let data = await requests.post(
          '/organizations/authorizations',
          this.org
        )
        location.href = data.url
      } catch (err) {
        this.errorMessage = err.message || '未知错误'
      }
    },
    async checkLogin () {
      let accessToken = getCookie('access_token')
      if (accessToken != null) {
        try {
          setAccessToken(accessToken)
          let data = await requests.get('/user')
          this.name = data.name
          this.ifLogin = true
        } catch (err) {
          console.log(err || 'unknown mistake')
        }
      }
    },
    logout () {
      delCookie('access_token')
      delCookie('userInfo')
      location.reload()
    }
  },
  mounted () {
    this.checkLogin()
  },
  computed: {
    ...mapState({
      ifLogin: 'ifLogin'
    })
  },
  watch: {
    ifLogin () {
      if (this.ifLogin) {
        this.checkLogin()
      }
    }
  }
}
</script>

<style scoped>
li {
  list-style: none;
}
.flex-row {
  display: flex;
  flex-direction: row;
  align-items: center;
}
.top-nav {
  height: 60px;
  padding: 5px;
  display: flex;
  background: #fff;
  box-shadow: 0 2px 2px #aaa;
  z-index: 1;
}
.links {
  flex-grow: 1;
}
</style>
