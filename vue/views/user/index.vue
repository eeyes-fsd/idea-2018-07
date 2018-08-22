<template>
    <div>
      <div class="row head-top">
        <img src="user.avatar" alt="头像太帅，加载不出来" class="userHead img-responsive img-circle center-block">
        <h3 class="text-center">{{ user.nickname }}</h3>
        <p class="text-center">{{ user.NetID }}</p>
      </div>
      <div class="row">
        <UserInfo class="col-md-3 panel panel-default" :user="user"></UserInfo>
        <div class="col-md-8  col-md-offset-1  panel panel-default userPanel">
          <div class="row">
            <h3>个人中心</h3>
            <hr/>
            <ul class="list-inline">
              <li class="tabMenu"><router-link :to="'/user/'+this.$route.params.id+'/article'"><span v-if="ifMe">我的发布</span><span v-else>他的发布</span></router-link></li>
              <li class="tabMenu"><router-link v-if="ifMe" :to="'/user/'+this.$route.params.id+'/message'">消息通知</router-link></li>
              <li class="tabMenu"><router-link :to="'/user/'+this.$route.params.id+'/favourite'"><span v-if="ifMe">我的收藏</span><span v-else>他的收藏</span></router-link></li>
            </ul>
          </div>
          <hr/>
          <div class="row content">
            <router-view :ifMe="this.ifMe"></router-view>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
<<<<<<< HEAD
  import requests, { setAccessToken } from '@/api/requests.js'
  import { getCookie } from "../../util";
  export default {
    name: "User",
    data() {
      return {
        user: {},
        ifMe: false
      }
    },
    methods:{
      async getInfo() {
        try {
          if(this.ifMe){
            let data = await requests.get('/user')
            this.user = data
          }else{
            let data = await requests.get('users/'+this.$route.params.id)
            this.user = data
          }
        } catch (err) {
          console.log(err)
          this.errorMessage = err.message || '未知错误'
        }
      },
      checkMe() { //判断访问的是否是自己的主页
        let netId = JSON.parse(getCookie('userInfo')).id
        let pageId = parseInt(this.$route.params.id)
        this.ifMe = netId===pageId
=======
import requests, { setAccessToken } from '@/api/requests.js'
import { getCookie } from "../../util";
import UserInfo from './UserInfo'

export default {
  name: "User",
  components: {
    UserInfo
  },
  data() {
    return {
      user: {},
      ifMe: false
    }
  },
  methods:{
    async getInfo() {
      setAccessToken(getCookie('access_token'))
      try {
        let data = await requests.get('/user')
        this.user = data
      } catch (err) {
        console.log(err)
        this.errorMessage = err.message || '未知错误'
>>>>>>> ac978b95c84311f32d8c19c6f1cab6e510d6992b
      }
    },
    checkMe() { //判断访问的是否是自己的主页
      console.log(JSON.parse(getCookie('userInfo')))
      let netid = JSON.parse(getCookie('userInfo')).NetID
    }
  },
  mounted (){
    this.getInfo()
    this.checkMe()
  },
}
</script>

<style scoped>
  /* 头像部分 */
  .head-top{
    padding: 36px;
    background-color: #fff;
    margin-bottom: 12px;
  }
  .userPanel{

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
