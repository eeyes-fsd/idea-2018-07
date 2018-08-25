<template>
  <div v-loading="loading">
    <Item v-for="(item, i) in notifications" :key="i" :notification="item"></Item>
    <Pagination
      :pagination="pagination"
      @change="getData"></Pagination>
  </div>
</template>

<script>
import requests from '@/api/requests.js'
import Item from './Item'
import Pagination from '@/components/Pagination'

export default {
  name: 'userMessage',
  components: {
    Item,
    Pagination
  },
  data () {
    return {
      loading: false,
      notifications: [],
      pagination: {},
    }
  },
  mounted () {
    this.getData()
  },
  methods: {
    async getData (page = 1) {
      this.loading = true
      let data = await requests.get(`/notifications?state=read&page=${page}`)
      this.pagination = data.pagination
      this.notifications = data.notifications
      this.loading = false
    }
  }
}
</script>

<style scoped>

</style>
