<template>
  <div v-loading="loading">
    <div class="no-content" v-if="articles.length == 0">
      <p>哎呀，这里什么都没有哦</p>
    </div>
    <div v-else>
      <Item v-for="(item,key) in articles" :key="key" :article="item"></Item>
    </div>
    <Pagination
      :pagination="pagination"
      @change="getMyArticle"
      class="text-center"></Pagination>
  </div>
</template>

<script>
import requests from '@/api/requests.js'
import Item from './Item'
import Pagination from '@/components/Pagination'

export default {
  name: "userArticle",
  components: {
    Item,
    Pagination
  },
  data() {
    return {
      loading: false,
      articles: [],
      pagination: {},
      comments: [],
      commentOn: 0,
    }
  },
  props:{
    isMe: Boolean
  },
  methods:{
    async getMyArticle(page) {
      this.loading = true
      this.currentPage = page
      let id = this.$route.params.id
      let perpage = 3 //每页显示的文章数目
      let url = `/articles?per_page=${perpage}&page=${page}` + (this.isMe ? '' : `&author_id=${id}&author_type=user`)
      try {
        let data =  await requests.get(url)
        this.pagination = data.pagination
        this.articles = data.articles
        this.totalPage = Math.ceil(data.pagination.total / perpage)
      }catch(e) {
        console.log(e || 'unknown mistake' )
      }
      this.loading = false
    },
  },
  mounted(){
    this.getMyArticle(1)//默认获取第一页的文章
  }
}
</script>

<style scoped lang="scss">
  .article-footer > p{
    padding-right: 1rem;
  }
  .no-content {
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
