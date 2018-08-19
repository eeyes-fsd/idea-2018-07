<template>
    <div>
      <div class="row head-top">
        <img src="user.avatar" alt="头像太帅，加载不出来" class="userHead img-responsive img-circle center-block">
        <h3 class="text-center">{{ user.nickname }}</h3>
        <p class="text-center">{{ user.NetID }}</p>
      </div>
      <div class="row">
        <div class="col-md-3 panel panel-default userInfo">
          <h3>个人信息 <button class="btn btn-default pull-right">编辑</button></h3>
          <hr/>
          <div>
            <h4>基本信息</h4>
            <p>昵称：{{ user.nickname }}</p>
            <p>个性签名:{{ user.signature }}</p>
            <h4>联系方式</h4>
            <p v-if="user.phone_visibility">手机：{{ user.phone }}</p>
            <p v-else>手机：保密</p>
            <p v-if="user.email_visibility">邮箱：{{ user.email }}</p>
            <p v-else>邮箱：保密</p>
            <p v-if="user.qq_visibility">QQ：{{ user.qq }}</p>
            <p v-else>QQ：保密</p>
          </div>
        </div>
        <div class="col-md-8  col-md-offset-1  panel panel-default userPanel">
          <div class="row">
            <h3>个人中心</h3>
            <hr/>
            <ul class="list-inline">
              <li class="tabMenu"><router-link to="/article">我的发布</router-link></li>
              <li class="tabMenu"><router-link to="/message">消息通知</router-link></li>
              <li class="tabMenu"><router-link to="/favourite">我的收藏</router-link></li>
            </ul>
          </div>
          <hr/>
          <div class="row content">
            <router-view></router-view>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
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
        setAccessToken(getCookie('access_token'))
        try {
          let data = await requests.get('/user')
          this.user = data
        } catch (err) {
          console.log(err)
          this.errorMessage = err.message || '未知错误'
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
