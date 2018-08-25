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
import { mapState } from 'vuex'
import requests from '@/api/requests.js'
import Item from './Item'
import Pagination from '@/components/Pagination'

export default {
  name: "userFavourite",
  components:{
    Item,
    Pagination
  },
  data() {
    return {
      loading: false,
      articles: {},
      pagination: {},
      page: 1,
      totalPage: 1,
      currentPage: 1,
      comments: [],
      commentOn: 0,
    }
  },
  methods:{
    async getMyArticle(page){
      this.loading = true
      this.currentPage = page
      let id = this.myUserId
      let perpage = 3 //每页显示的文章数目
      try {
        //这里写获取收藏的url
        let data =  await requests.get(`/favorites?author_type=user&per_page=${perpage}&author_id=${id}`)
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
  },
  computed: {
    ...mapState({
      userInfo: 'userInfo'
    }),
    myUserId () {
      return this.userInfo.id
    },
  }
}
</script>

<style scoped lang="scss">
  .article-footer>p{
    padding-right: 1rem;
  }
</style>
