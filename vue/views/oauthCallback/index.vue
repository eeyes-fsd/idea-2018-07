<template>
  <div class="oauth-callback">
    <p>登录中……请稍后</p>
    <p>当前登录code为：{{ code }}</p>
    <p>登录成功返回参数：{{ args }}</p>
  </div>
</template>

<script>
import requests from '@/api/requests.js'
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
    async loginCallback() {
      try {
        let data = await requests.get('/users/authorizations/callback', { code: this.code })
        this.args = data
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
