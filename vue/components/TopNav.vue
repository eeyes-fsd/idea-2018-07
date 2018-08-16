<template>
    <div>
      <h2>顶部导航栏</h2>
      <router-link to="/">主页</router-link>
      <router-link to="/user">个人界面</router-link>
    </div>
</template>

<script>
  import requests, { getLoginType } from '@/api/requests.js'
    export default {
      name: "top-nav",
      data() {
        return {}
      },
      methods:{},
    }
</script>

<style scoped>

</style>
