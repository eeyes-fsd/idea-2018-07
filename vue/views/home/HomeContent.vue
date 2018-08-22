<template>
  <div class="home-content">
    <div class="articles">
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
    <div class="pagination">
      这是临时分页器
      <input type="text" v-model="page">
      <button @click="loadContent()">跳转</button>
    </div>
    <div class="loading-cover" v-show="loading">
      <p>加载中</p>
    </div>
  </div>
</template>

<script>
import requests from '@/api/requests'
import IdeaCard from './IdeaCard'

export default {
  name: 'HomeContent',
  components: {
    IdeaCard
  },
  data () {
    return {
      articles: [],
      loading: false,
      page: 1
    }
  },
  mounted () {
    this.loadContent()
  },
  methods: {
    async loadContent() {
      this.loading = true
      let data = await requests.get(`/articles?category_id=${this.categoryId}&page=${this.page}`)
      this.articles = data.articles
      this.loading = false
    }
  },
  watch: {
    '$route' () {
      this.loadContent()
    }
  },
  computed: {
    categoryId () {
      return this.$route.params.id
    }
  }
}
</script>

<style lang="scss" scoped>
.home-content {
  position: relative;
  width: 100%;
}
.articles {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
}
.loading-cover {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background: rgba(#fff, .5);
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
}
</style>
