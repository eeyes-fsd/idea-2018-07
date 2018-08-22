<template>
  <div class="oauth-callback">
    <p>登录中……请稍后</p>
    <MessageBox
      :visible.sync="hasError"
      type="danger"
      title="错误"
      :message="errorMessage"
      :autoHide="false"></MessageBox>
  </div>
</template>

<script>
import { mapMutations, mapActions } from 'vuex'
import requests from '@/api/requests.js'
import MessageBox from '@/components/MessageBox'

export default {
  name: 'OauthCallback',
  components: {
    MessageBox
  },
  data () {
    return {
      code: '',
      hasError: false,
      errorMessage: ''
    }
  },
  mounted () {
    let code = this.$route.query.code
    // 获取登录跳转的code
    if (code && code.length > 0) {
      this.code = code
      this.$router.push('/oauth/callback') // 清空请求参数
      this.loginCallback()
    } else {
      this.errorMessage = '登录失败，请重新登录'
      this.hasError = true
    }
  },
  methods: {
    ...mapActions({
      initialize: 'initialize'
    }),
    /**
     * 普通用户登录
     */
    async loginCallback() {
      try {
        // 使用AccessToken开始初始化
        this.initialize({ code: this.code })
        // setLoginType('user')  // 设定登录用户的类型 暂时不管这个功能了
        this.$router.push('/') // 跳转回首页
      } catch (err) {
        this.errorMessage = err.message || '未知错误'
        this.hasError = true
      }
    }
  }
}
</script>

<style>

</style>
