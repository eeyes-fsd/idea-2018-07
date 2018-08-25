<template>
  <nav class="top-nav navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button"
          class="navbar-toggle collapsed"
          @click="active = !active"
          :aria-expanded="active">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <router-link to="/">
          <img src="" alt="logo" class="logo">
        </router-link>
      </div>
      <div class="collapse navbar-collapse" :class="{ 'in': active }">
        <NavMenu></NavMenu>
        <div class="nav navbar-right">
          <div class="welcome" v-if="!isLogin">
            <p>欢迎访问创意工坊，请先<a href="javascript:;" @click="userLogin">登录</a></p>
          </div>
          <ul class="actions nav navbar-nav" v-else>
            <li>
              <router-link :to="`/user/${myUserId}/article`">个人中心</router-link>
            </li>
            <li>
              <router-link to="/publish">发表文章</router-link>
            </li>
            <li>
              <a href="javascript:;" @click="logout()">退出登录</a>
            </li>
          </ul>
        </div>
        <div class="navbar-form navbar-right search-box">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="文章/用户/组织" v-model="searchCon">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" @click="search()">搜索</button>
            </span>
          </div>
        </div>
      </div>
    </div>

    <MessageBox
      :visible.sync="error"
      title="错误"
      type="danger"
      :message="errorMessage"></MessageBox>
  </nav>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
import requests from '@/api/requests.js'
import NavMenu from './NavMenu'
import MessageBox from './MessageBox'

export default {
  name: 'TopNav',
  data() {
    return {
      active: true,
      name: '',
      org: {
        email: '',
        password: ''
      },
      searchCon: '',
      error: false,
      errorMessage: ''
    }
  },
  components: {
    NavMenu,
    MessageBox
  },
  methods: {
    ...mapMutations({
      commitLoginToVuex: 'login'
    }),
    ...mapActions({
      logout: 'logout'
    }),
    async userLogin () {
      //个人登录
      try {
        let data = await requests.get('/users/authorizations')
        location.href = data.url
      } catch (err) {
        this.errorMessage = err.message || '未知错误'
      }
    },
    async orgLogin () {
      //社团登录
      try {
        let data = await requests.post(
          '/organizations/authorizations',
          this.org
        )
        location.href = data.url
      } catch (err) {
        this.errorMessage = err.message || '未知错误'
      }
    },
    async search () {
      this.$router.push({ name: 'search', query: { key : this.searchCon } })
    },
  },

  computed: {
    ...mapState({
      isLogin: 'isLogin',
      userInfo: 'userInfo'
    }),
    myUserId () {
      return this.userInfo.id
    }
  },
}
</script>

<style scoped>
li {
  list-style: none;
}
.top-nav {
  /* height: 60px; */
  padding: 5px;
  background: #fff;
  box-shadow: 0 2px 2px #aaa;
  z-index: 1;
}
.search-box{
  width: 15em;
}
.links {
  flex-grow: 1;
}
p {
  margin: 11px 0;
}
</style>
