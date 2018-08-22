<template>
  <div class="home">
    <img :src="img.back" alt="" class="img-responsive">
    <router-view></router-view>
  </div>
</template>

<script>
import requests, { getLoginType }from '@/api/requests.js'
import Example from '@/components/Example'
import { getCookie, setCookie } from "../../util"
import { setLoginType} from "../../api/requests"
import { mapMutations } from 'vuex'

export default {
  components: {
    Example
  },
  data () {
    return {
      errorMessage: '暂时没有错误发生',
      checkState: false,
      ifLogin: false,
      img:{
        back: '../img/index/background.png'
      }
    }
  },
  methods: {
    ...mapMutations({
      commitLoginToVuex: 'login'
    }),
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
          let data = await requests.get('/user')
          this.commitLoginToVuex()
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
