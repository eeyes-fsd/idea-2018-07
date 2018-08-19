<template>
    <div>
      <h2>顶部导航栏</h2>
      <p>
        <router-link to="/">创意工坊</router-link>
        <span  v-if="ifLogin">欢迎你，{{ name }}</span>
        <span v-else>请 <a @click="userLogin">登录</a></span>
        <router-link v-if="ifLogin" to="/article">个人界面</router-link>
        <router-link to="/publish" v-if="ifLogin">发表文章</router-link>
        <a  v-if="ifLogin" @click="logout">退出登录</a>
      </p>
    </div>
</template>

<script>
  import requests, { getLoginType, setAccessToken } from '@/api/requests.js'
  import { getCookie, delCookie } from "../util";

  export default {
      name: "top-nav",
      data() {
        return {
          name: '',
          ifLogin: false,
          org: {
            email: '',
            password: ''
          },
        }
      },
      methods:{
        async userLogin () {//个人登录
          try {
            let data = await requests.get('/users/authorizations')
            location.href = data.url
          } catch (err) {
            this.errorMessage = err.message || '未知错误'
          }
        },
        async orgLogin () {//社团登录
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
              this.name = data.name
              this.ifLogin = true
            }catch (err) {
              console.log(err || 'unknown mistake')
            }
          }
        },
        logout (){
          delCookie('access_token')
          delCookie('userInfo')
          location.reload()
        }
      },
      mounted() {
        this.checkLogin()
      },
      computed:{
        getLogin(){
          return this.$store.state.ifLogin
        }
      },
      watch: {
        getLogin(){
          if(this.$store.state.ifLogin){
            this.checkLogin()
          }
        }
      }
    }
</script>

<style scoped>

</style>
