<template>
  <div class="oauth-callback">
    <p>登录中……请稍后</p>
    <p>当前登录code为：{{ code }}</p>
    <p>登录成功返回参数：{{ args }}</p>
  </div>
</template>

<script>
import requests, { setLoginType } from '@/api/requests.js'
import { setCookie } from "../../util";

export default {
  name: 'OauthCallback',
  data () {
    return {
      code: '',
      args: {}
    }
  },
  mounted () {
    let code = this.$route.query.code
    if (code && code.length > 0) {
      this.code = code
      this.$router.push('/oauth/callback') // 清空请求参数
      this.loginCallback()
    }
  },
  methods: {
    //普通用户登录
    async loginCallback() {
      try {
        let data = await requests.post('/users/authorizations/callback', { code: this.code })
        this.args = data
        setCookie( 'access_token', data.access_token,  data.expires_in) //保存cookie
        setLoginType('user')  //设定登录用户的类型
        this.$router.push('/')  //跳转回首页
      } catch (err) {
        this.errorMessage = err.message || '未知错误'
      }
    }
  },
  computed: {
    shortCode() {
      return this.code ? this.code.substr(0, 16) + '...' : '无code'
    }
  }
}
</script>

<style>

</style>
