<template>
  <div class="user__message-item">
    <div v-if="isPrivateMessage">
      <div class="media">
        <div class="media-left">
          <img class="media-object avatar" :src="avatar" alt="authorName">
        </div>
        <div class="media-body d-flex flex-row">
          <div>
            <h4 class="media-heading">{{ authorName }}</h4>
            <p class="message-body">{{ messageBody }}</p>
          </div>
          <div>
            <p>{{ time }}</p>
            <span class="glyphicon glyphicon-trash"></span>
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      <p>不支持的类型：{{ notification.type }}</p>
      <p>消息通知全内容：{{ notification }}</p>
    </div>
  </div>
</template>

<script>
import { defaultAvatar } from './data'

export default {
  name: 'Item',
  props: {
    notification: Object
  },
  computed: {
    isPrivateMessage () {
      return this.notification.type == 'private_massage'
    },
    avatar () {
      return this.notification.author_avatar || defaultAvatar
    },
    authorName () {
      return this.notification.author_name
    },
    messageBody () {
      return this.notification.body
    },
    time () {
      return this.notification.created_at.replace(/\s*\d+:\d+:\d+/g, '')
    }
  }
}
</script>

<style>
.user__message-item .avatar {
  border-radius: 50%;
}
</style>
