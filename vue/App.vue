<template>
  <div class="container">
    <top-nav></top-nav>
    <router-view v-if="render"></router-view>
    <div class="loading-cover app-cover" v-else>
      <p>加载中……</p>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import TopNav from './components/TopNav'

export default {
  name: 'APP',
  components: {
    TopNav
  },
  mounted () {
    if (!this.callback) {
      this.initialize()
    }
  },
  computed: {
    ...mapState({
      init: 'init'
    }),
    callback () {
      return this.$route.name == 'oauthCallback';
    },
    render () {
      return this.callback || this.init
    }
  },
  methods: {
    ...mapActions({
      initialize: 'initialize'
    })
  }
}
</script>

<style lang="scss">
body {
  background: #fff;
}
// 全局样式
.loading-cover {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background: rgba(#fff, .65);
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
}
</style>

<style lang="scss">
.app-cover {
  min-height: 600px;
}
.container {
  background-color: #fff;
}
</style>
