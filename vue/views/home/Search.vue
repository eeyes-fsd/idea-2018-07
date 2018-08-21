<template>
  <div>
    <h2>下面是搜索：<strong>{{ this.$route.query.key }} 的结果</strong></h2>
    <div v-if="articles===null">
      未找到相关文章
    </div>
    <div v-else>
      <div v-for="(item,key) in articles" :key=key>
        文章
        {{ item.title }}
      </div>
    </div>
    <div v-if="users===null">
      未找到相关用户
    </div>
    <div v-else>
      <User v-for="(item,key) in users" :key=key :user="item"></User>
    </div>
  </div>
</template>

<script>
import requests from '@/api/requests.js'
import User from './User'
export default {
  name: 'Search',
  components:{
    User
  },
  data () {
    return {
      articles: null,
      users: null,
      organization: null,
      key: '',
    }
  },
  methods: {
    async searchArticles () { //搜索文章
      try{
        let count = 8
        let data = await requests.get('/search/article?wd='+this.$route.query.key+'&per_page='+count)
        this.articles = data.articles
      }catch (e) {
        this.articles = null
        console.log(e)
      }
    },
    async searchUsers () {  //搜索用户
      try{
        let count = 8
        let data = await requests.get('/search/users?wd='+this.$route.query.key+'&per_page='+count)
        this.users = data.users
      }catch (e) {
        this.users = null
        console.log(e)
      }
    }
  },
  mounted () {
    this.key = this.$route.query.key
    this.searchArticles()
    this.searchUsers()
  },
  computed:{

  },
  watch: {
    $route(){
      this.key = this.$route.query.key
    },
    key() {
      this.searchArticles()
      this.searchUsers()
    }
  }
}
</script>

<style>

</style>
