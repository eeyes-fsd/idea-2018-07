<template>
    <div>
      <div class="row head-top">
        <img :src="user.avatar" alt="头像太帅，加载不出来" class="userHead img-responsive img-circle center-block">
        <h3 class="text-center">{{ user.nickname }}</h3>
        <p class="text-center">{{ user.NetID }}</p>
      </div>
      <div class="row">
        <UserInfo class="col-md-3 panel panel-default" :user="user" :isMe="isMySelf"></UserInfo>
        <div class="col-md-8  col-md-offset-1  panel panel-default userPanel">
          <div class="row">
            <h3>个人中心</h3>
            <hr>
            <ul class="list-inline">
              <li class="tabMenu">
                <router-link :to="`/user/'${$route.params.id}/article`"
                  >{{ isMySelf ? '我的' : '他的' }}发布</router-link>
              </li>
              <li class="tabMenu" v-if="isMySelf">
                <router-link :to="`/user/'${$route.params.id}/message`"
                  >消息通知</router-link>
              </li>
              <li class="tabMenu">
                <router-link :to="`/user/'${$route.params.id}/favourite`"
                  >{{ isMySelf ? '我的' : '他的' }}收藏</router-link>
              </li>
            </ul>
          </div>
          <hr>
          <div class="row content">
            <router-view :isMe="this.isMySelf"></router-view>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import requests from '@/api/requests.js'
import UserInfo from './UserInfo'
import Dialog from '@/components/Dialog'

export default {
  name: "User",
  data() {
    return {
      user: {},
      editing: false,
    }
  },
  components:{
    UserInfo,
  },
  methods:{
    async getInfo() {
      try {
        if (this.isMe) {
          this.user = this.userInfo
        } else {
          let data = await requests.get('/users/'+this.$route.params.id)
          this.user = data
        }
      } catch (err) {
        console.log(err)
        this.errorMessage = err.message || '未知错误'
      }
    },
    uploadHead () {
      this.editing = true
    },
  },
  mounted (){
    this.getInfo()
  },
  computed: {
    ...mapState({
      isLogin: 'isLogin',
      userInfo: 'userInfo'
    }),
    myUserId () {
      return this.userInfo.id
    },
    isMySelf () {
      return this.myUserId == parseInt(this.$route.params.id)
    }
  }
}
</script>

<style scoped>
  /* 头像部分 */
  .head-top{
    padding: 36px;
    background-color: #fff;
    margin-bottom: 12px;
  }
  /* 个人信息模块 */
  .tabMenu{
    display: block;
    float:left;
    width: 33%;
    text-align:center;
    padding-bottom: 6px;
  }
  .tabMenu>a{
    text-decoration:none;
    color: #636b6f;
  }
  .active{
    border-bottom: #000 solid 2px;
  }
  /* 个人中心 */
  .userPanel>div{
    padding-left: 15px;
  }
  .content{
    margin-top: 20px;
  }
</style>
