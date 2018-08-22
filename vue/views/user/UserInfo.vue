<template>
  <div class="user__info">
    <h3>个人信息 <button class="btn btn-default pull-right edit-btn" @click="editing = true">编辑</button></h3>
    <hr>
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
    <Dialog :visible.sync="editing" @confirm="submit">
      <div class="form-group">
        <label>昵称</label>
        <input v-model="form.nickname" type="text" class="form-control">
      </div>
      <div class="form-group">
        <label>个性签名</label>
        <input v-model="form.signature" type="text" class="form-control">
      </div>
      <div class="form-group">
        <label>手机</label>
        <input v-model="form.phone" type="text" class="form-control">
      </div>
      <div class="form-group">
        <label>邮箱</label>
        <input v-model="form.email" type="text" class="form-control">
      </div>
      <div class="form-group">
        <label>QQ</label>
        <input v-model="form.qq" type="text" class="form-control">
      </div>
    </Dialog>
  </div>
</template>

<script>
import requests from '@/api/requests.js'
import Dialog from '@/components/Dialog'
import { getCookie } from '@/util'

export default {
  name: 'UserInfo',
  props: {
    user: Object
  },
  components: {
    Dialog
  },
  data () {
    return {
      editing: false,
      form: {
        nickname: '',
        signature: '',
        phone: '',
        email: '',
        qq: ''
      }
    }
  },
  methods: {
    async submit () {
      let userInfo = JSON.parse(getCookie('userInfo'))
      let id = userInfo.id
      await requests.put(`/users/${id}`, this.form)
    }
  }
}
</script>

<style>
.user__info > .edit-btn {
  outline: none;
}
</style>
