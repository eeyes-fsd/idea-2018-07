<template>
  <div>
    <Item v-for="(item, i) in notifications" :key="i" :notification="item"></Item>
  </div>
</template>

<script>
import requests from '@/api/requests.js'
import Item from './Item'

export default {
  name: 'userMessage',
  components: {
    Item
  },
  data () {
    return {
      notifications: []
    }
  },
  mounted () {
    this.getData()
  },
  methods: {
    async getData () {
      let data = await requests.get('/notifications?state=read')
      this.notifications = data.notifications
    }
  }
}
</script>

<style scoped>

</style>
