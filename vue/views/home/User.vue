<template>
  <div>
    <img :src="user.avatar" alt="">
    <h1>{{ user.nickname }}</h1>
  </div>
</template>

<script>
export default {
  name: 'User',
  props:{
    user: Object
  }
}
</script>

<style>

</style>
