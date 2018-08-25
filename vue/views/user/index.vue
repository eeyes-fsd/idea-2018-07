<template>
  <div>
    <div class="row head-top">
      <a href="javascript:;" @click="handleEditAvatar">
        <img :src="user.avatar" alt="头像太帅，加载不出来" class="userHead img-responsive img-circle center-block">
      </a>
      <h3 class="text-center">{{ user.nickname }}</h3>
      <p class="text-center">{{ user.NetID }}</p>
    </div>
    <div class="row">
      <UserInfo class="col-md-3 panel panel-default userInfo" :user="user" :isMe="isMySelf"></UserInfo>
      <div class="col-md-8  panel panel-default userPanel">
        <div class="row">
          <h3>个人中心</h3>
          <hr style="border-top:3px solid #655e5e; width:90%;" />
          <ul class="list-inline">
            <li class="tabMenu">
              <router-link :to="`/user/${currentId}/article`"
                >{{ isMySelf ? '我的' : '他的' }}发布</router-link>
            </li>
            <li class="tabMenu" v-if="isMySelf">
              <router-link :to="`/user/${currentId}/message`"
                >消息通知</router-link>
            </li>
            <li class="tabMenu">
              <router-link :to="`/user/${currentId}/favourite`"
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
    <Dialog :visible.sync="editingAvatar" position="center" @confirm="handleUploadAvatar">
      <el-upload
        class="avatar-uploader"
        :action="`/api/users/avatar/${currentId}`"
        ref="avatarUploader"
        name="avatar"
        :headers="avatarHeaders"
        :show-file-list="false"
        :auto-upload="false"
        :before-upload="beforeAvatarUpload"
        :on-success="handleAvatarSuccess">
        <img v-if="imageUrl" :src="imageUrl" class="avatar">
        <i v-else class="el-icon-plus avatar-uploader-icon"></i>
      </el-upload>
    </Dialog>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import requests from '@/api/requests.js'
import UserInfo from './UserInfo'
import Dialog from '@/components/Dialog'

export default {
  name: "User",
  data() {
    return {
      user: {},
      editingAvatar: false,
      imageUrl: ''
    }
  },
  components:{
    UserInfo,
    Dialog
  },
  methods:{
    ...mapActions({
      refreshUserInfo: 'refreshUserInfo'
    }),
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
    handleEditAvatar () {
      if (this.isMySelf) {
        this.editingAvatar = true
      }
    },
    handleChooseFile (event) {
      let file = event.srcElement.files[0]
      this.imageUrl = URL.createObjectURL(file);
    },
    handleUploadAvatar () {
      this.$refs.avatarUploader.submit()
    },
    handleAvatarSuccess(res, file) {
      this.refreshUserInfo(() => {
        this.user = this.userInfo
        this.$message.success('上传成功');
        this.editingAvatar = false
      })
    },
    beforeAvatarUpload (file) {
      var isJPG = file.type === 'image/jpeg';
      var isLt2M = file.size / 1024 / 1024 < 2;
      if (!isJPG) {
        this.$message.error('上传头像图片只能是 JPG 格式!');
      }
      if (!isLt2M) {
        this.$message.error('上传头像图片大小不能超过 2MB!');
      }
      return isJPG && isLt2M;
    }
  },
  mounted (){
    this.getInfo()
    this.$refs.avatarUploader.$el.children[0].children[1].onchange = this.handleChooseFile
  },
  watch: {
    currentId () {
      this.getInfo()
    }
  },
  computed: {
    ...mapState({
      isLogin: 'isLogin',
      userInfo: 'userInfo',
      accessToken: 'accessToken'
    }),
    currentId () {
      return this.$route.params.id
    },
    myUserId () {
      return this.userInfo.id
    },
    isMySelf () {
      return this.myUserId == parseInt(this.currentId)
    },
    avatarHeaders () {
      return {
        Authorization: `Bearer ${this.accessToken}`
      }
    }
  }
}
</script>

<style>
.avatar-uploader {
  text-align: center;
}
.avatar-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.avatar-uploader .el-upload:hover {
  border-color: #409EFF;
}
.avatar-uploader input {
  display: none;
}
.avatar-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.avatar-uploader .avatar {
  width: 178px;
  height: 178px;
  display: block;
}
</style>


<style scoped lang="scss">
  /* 头像部分 */
  .head-top{
    padding: 36px;
    background-color: #fff;
    margin: 12px -15px;
    background-image: url('/img/user/userback.jpg');
  }
  /* 个人信息模块 */
  .panel{
    border-radius: 0;
    overflow: hidden;
  }
  .tabMenu{
    display: block;
    float:left;
    width: 33%;
    text-align:center;
    padding-bottom: 6px;
    a{
      text-decoration:none;
      color: #636b6f;
    }
  }
  .active{
    border-bottom: #000 solid 2px;
  }
  .userHead{
    width: 10em;
    height: 10em;
  }
  .userInfo{
    margin: 12px 12px;
  }
  /* 个人中心 */
  .userPanel{
    margin: 12px 12px;
  }
  .userPanel>div{
    padding-left: 15px;
  }
  .content{
    margin-top: 20px;
  }
</style>
