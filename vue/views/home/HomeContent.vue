<template>
  <div class="home-content">
    <div class="no-articles" v-if="articles.length == 0">
      <p>哎呀，这里什么都没有哦</p>
    </div>
    <div class="articles">
      <IdeaCard
        v-for="(item,key) in articles"
        :key="key"
        class="article"
        img-src=""
        :title="item.title"
        :id="item.id"
        :author="item.author.nickname"
        :datetime="item.created_at"
        :liked="item.like_count"
        :viewed="item.view_count">
      </IdeaCard>
    </div>
    <Pagination
      :pagination="pagination"
      @change="change"></Pagination>
    <div class="loading-cover" v-show="loading">
      <p>加载中</p>
    </div>
  </div>
</template>

<script>
import requests from '@/api/requests'
import IdeaCard from './IdeaCard'
import Pagination from '@/components/Pagination'

export default {
  name: 'HomeContent',
  components: {
    IdeaCard,
    Pagination
  },
  data () {
    return {
      articles: [],
      pagination: {},
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
      this.pagination = data.pagination
      this.articles = data.articles
      this.loading = false
    },
    change (targetPage) {
      this.page = targetPage
      this.loadContent()
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
  .article {
    margin: 14px;
  }
}
.no-articles {
  min-height: 260px;
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  p {
    font-size: 1.4em;
  }
}
</style>
