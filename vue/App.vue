<template>
  <div class="container">
    <top-nav></top-nav>
    <router-view></router-view>
  </div>
</template>

<script>
  import TopNav from './components/TopNav'
  export default {
  name: 'APP',
    components: {
      TopNav
    },
}
</script>

<style lang="scss">

</style>
