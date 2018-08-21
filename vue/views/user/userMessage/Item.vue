<template>
  <div class="user__message-item">
    <div v-if="canShow">
      <div class="media">
        <div class="media-left">
          <img class="media-object avatar" :src="avatar" :alt="name">
        </div>
        <div class="media-body">
          <div>
            <h4 class="media-heading">{{ title }}</h4>
            <p class="message-body">{{ body }}</p>
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
import * as process from './process'

export default {
  name: 'Item',
  props: {
    notification: Object
  },
  methods: {
    /**
     * 获取notification的某个属性
     * 根据process来决定如何获取
     */
    getAttr (key) {
      let noti = this.notification
      let mixed = process[noti.type][key]

      // 如果是函数，就按照函数返回值获取
      if (mixed instanceof Function) {
        return mixed(noti)
      } else {
        // 否则直接索引键名
        return noti[mixed]
      }
    }
  },
  computed: {
    canShow () {
      return process[this.notification.type] != undefined
    },
    avatar () {
      return this.getAttr('avatar') || defaultAvatar
    },
    name () {
      return this.getAttr('name')
    },
    title () {
      return this.getAttr('name')
    },
    body () {
      return this.getAttr('body')
    },
    time () {
      return this.getAttr('time').replace(/\s*\d+:\d+:\d+/g, '')
    }
  }
}
</script>

<style>
.user__message-item .avatar {
  border-radius: 50%;
}
</style>
