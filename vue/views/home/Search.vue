<template>
  <div>
    <h2>下面是搜索：<strong>{{ this.$route.query.key }} 的结果</strong></h2>
    <div v-if="articles===null">
      未找到相关文章
    </div>
    <div v-else class="search-articles">
      <IdeaCard
        v-for="(item,key) in articles"
        :key="key"
        class="article"
        img-src=""
        :title="item.title"
        :author="item.author.nickname"
        :datetime="item.created_at"
        :liked="item.like_count"
        :viewed="item.view_count">
      </IdeaCard>
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
import IdeaCard from './IdeaCard'

export default {
  name: 'Search',
  components:{
    User,
    IdeaCard
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

<style lang="scss" scoped>
.search-articles {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  .article {
    margin: 10px;
  }
}
</style>
