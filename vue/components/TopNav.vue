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
    <div class="search_box">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="文章/用户/组织" v-model="searchCon">
        <span class="input-group-btn">
            <button class="btn btn-default" type="button" @click="search()">搜索</button>
        </span>
      </div>
    </div>
    <div class="welcome" v-if="!ifLogin">
      <p>欢迎访问创意工坊，请先<a href="javascript:;" @click="userLogin">登录</a></p>
    </div>
    <div class="actions" v-else>
      <router-link :to="`/user/${myUserId}/article`">个人中心</router-link>
      <router-link to="/publish">发表文章</router-link>
      <button @click="logout()">退出登录</button>
    </div>
  </nav>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import requests, { getLoginType, setAccessToken } from '@/api/requests.js'
import { getCookie, delCookie, autoRefreshToken } from '../util'

export default {
  name: 'TopNav',
  data() {
    return {
      name: '',
      org: {
        email: '',
        password: ''
      },
      searchCon: '',
    }
  },
  methods: {
    ...mapMutations({
      commitLoginToVuex: 'login'
    }),
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
      autoRefreshToken()
      let accessToken = getCookie('access_token')
      if (accessToken != null) {
        try {
          setAccessToken(accessToken)
          let data = await requests.get('/user')
          this.name = data.name
          // this.ifLogin = true
          this.commitLoginToVuex()
        } catch (err) {
          console.log(err || 'unknown mistake')
        }
      }
    },
    async search () {
      this.$router.push({ name: 'search', query: { key : this.searchCon } })
    },
    logout () {
      delCookie('access_token')
      delCookie('userInfo')
      delCookie('laravel_session')
      location.href = "https://account.eeyes.net/logout"
    },
  },
  mounted () {
    this.checkLogin()
  },
  computed: {
    ...mapState({
      ifLogin: 'ifLogin'
    }),
    myUserId () {
      return JSON.parse(getCookie('userInfo')).id
    }
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
.search_box{
  width: 20%;
}
.links {
  flex-grow: 1;
}
</style>
