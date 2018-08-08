<template>
  <div class="oauth-callback">
    <p>登录中……请稍后</p>
    <p>当前登录code为：{{ code }}</p>
  </div>
</template>

<script>
export default {
  name: 'OauthCallback',
  data () {
    return {
      code: ''
    }
  },
  mounted () {
    let code = this.$route.query.code
    if (code && code.length > 0) {
      this.code = code
      this.$router.push('/') // 页面跳转
    }
  },
}
</script>

<style>

</style>
