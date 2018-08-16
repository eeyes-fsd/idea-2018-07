<template>
    <div>
      <h2>个人信息页面</h2>
    </div>
</template>

<script>
  import requests, { setAccessToken } from '@/api/requests.js'
  import { getCookie } from "../../util";
  export default {
    name: "User",
    data() {
      return {

      }
    },
    mounted: function(){
      this.getInfo()
    },
    methods:{
      async getInfo() {
        setAccessToken(getCookie('access_token'))
        try {
          let data = await requests.get('/users')
          console.log(data)
        } catch (err) {
          console.log(err)
          this.errorMessage = err.message || '未知错误'
        }
      }
    },
  }
</script>

<style scoped>

</style>
