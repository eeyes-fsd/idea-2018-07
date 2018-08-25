<template>
  <div v-loading="loading">
    <Item v-for="(item,key) in articles" :key="key" :article="item"></Item>
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
      articles: {},
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
  .article-footer>p{
    padding-right: 1rem;
  }
</style>
