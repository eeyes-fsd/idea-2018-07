<template>
  <div>
    <h2>以下是：<strong>{{ this.$route.query.key }} 的搜索结果</strong></h2>
    <h5 class="section-title">相关文章</h5>
    <hr>
    <div v-if="articles===null">
      <p class="no-found">未找到相关文章</p>
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
    <h5 class="section-title">相关用户</h5>
    <hr>
    <div v-if="users===null">
      <p class="no-found">未找到相关用户</p>
    </div>
    <div v-else>
      <UserDetails
        v-for="(item, key) in users"
        :key="key"
        avatar=""
        :name="item.nickname"
        :signature="item.signature"></UserDetails>
    </div>
  </div>
</template>

<script>
import requests from '@/api/requests.js'
import UserDetails from './UserDetails'
import IdeaCard from './IdeaCard'

export default {
  name: 'Search',
  components:{
    UserDetails,
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
.section-title {
  padding-left: 12px;
  font-size: 1.4em;
}
.section-title + hr {
  margin: 8px 2px 4px;
}
.no-found {
  padding-left: 12px;
}
</style>
