<template>
  <div class="home">
    <img :src="img.back" alt="" class="img-responsive">
    <router-view></router-view>
  </div>
</template>

<script>
import { mapMutations } from 'vuex'
import requests from '@/api/requests.js'

export default {
  data () {
    return {
      errorMessage: '暂时没有错误发生',
      checkState: false,
      img: {
        back: '../img/index/background.png'
      }
    }
  },
  computed: {
    // loginType() {
    //   const trans = {
    //     'null': '未登录',
    //     'user': '个人用户',
    //     'org': '社团'
    //   }
    //   return trans[getLoginType()] || '错误的登陆身份类型'
    // },
  },
}
</script>

<style lang="scss">

</style>
