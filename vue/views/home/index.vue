<template>
  <div class="home">
    <header>
      <button @click="userLogin">个人登录</button>
      <input type="text" v-model="org.email" placeholder="邮箱地址">
      <input type="text" v-model="org.password" placeholder="密码">
      <button @click="orgLogin">社团登录</button>
      <p>您当前的登陆身份类型：{{ loginType }}</p>
      <router-link to="/publish" v-if="ifLogin">发表文章</router-link>
    </header>
    <img src="" alt="" class="row">
  </div>
</template>

<script>
import requests, { getLoginType }from '@/api/requests.js'
import Example from '@/components/Example'
import { getCookie, setCookie } from "../../util"
import {setAccessToken, setLoginType} from "../../api/requests"

export default {
  components: {
    Example
  },
  data () {
    return {
      org: {
        email: '',
        password: ''
      },
      errorMessage: '暂时没有错误发生',
      checkState: false,
      ifLogin: false
    }
  },
  methods: {
    async userLogin () {
      try {
        let data = await requests.get('/users/authorizations')
        location.href = data.url
      } catch (err) {
        this.errorMessage = err.message || '未知错误'
      }
    },
    async orgLogin () {
      try {
        let data = await requests.post('/organizations/authorizations', this.org)
        location.href = data.url
      } catch (err) {
        this.errorMessage = err.message || '未知错误'
      }
    },
    async checkLogin(){
      let accessToken = getCookie('access_token')
      if(accessToken != null){
        try {
          setAccessToken(accessToken)
          let data = await requests.get('/user')
          this.ifLogin = true
          this.$store.commit('login')
          setCookie('userInfo', JSON.stringify(data))
        }catch (err) {
          this.errorMessage = err.message || '未知错误'
        }
      }
    },
  },
  computed: {
    loginType() {
      const trans = {
        'null': '未登录',
        'user': '个人用户',
        'org': '社团'
      }
      return trans[getLoginType()] || '错误的登陆身份类型'
    },
  },
  mounted() {
    this.checkLogin()
  },
}
</script>

<style lang="scss">

</style>
