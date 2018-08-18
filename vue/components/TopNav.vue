<template>
    <div>
      <h2>顶部导航栏</h2>
      <router-link to="/">创意工坊</router-link>
      欢迎你，{{ name }}
      <router-link v-if="ifLogin" to="/user">个人界面</router-link>
      <button  v-if="ifLogin">退出登录</button>
    </div>
</template>

<script>
  import requests, { getLoginType, setAccessToken } from '@/api/requests.js'
  import { getCookie } from "../util";

  export default {
      name: "top-nav",
      data() {
        return {
          name: '',
          ifLogin: false
        }
      },
      methods:{
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
      },
      mounted() {
        this.checkLogin()
      }
    }
</script>

<style scoped>

</style>
