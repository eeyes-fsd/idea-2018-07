<template>
  <div>
    <header>
      <button @click="userLogin">个人登录</button>
      <button>社团登录</button>
      <p>{{ errorMessage }}</p>
    </header>
  </div>
</template>

<script>
import requests from '@/api/requests.js'
import Example from '@/components/Example'
export default {
  components: {
    Example
  },
  data () {
    return {
      errorMessage: '暂时没有错误发生'
    }
  },
  methods: {
    async userLogin () {
      try {
        let data = await requests.get('/api/users/authorizations')
        location.href = data.url
      } catch (err) {
        this.errorMessage = err.message || '未知错误'
      }
    }
  }
}
</script>

<style lang="scss">

</style>
