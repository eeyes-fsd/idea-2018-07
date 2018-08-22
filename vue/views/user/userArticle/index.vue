<template>
    <div>
      <Item v-for="(item,key) in articles" :key="key" :article="item"></Item>
      <nav aria-label="article_page">
        <ul class="pagination">
          <li>
            <a aria-label="Previous" v-if="currentPage-1" @click="getMyArticle(currentPage-1)">
            <span aria-hidden="true">&laquo;</span>
            </a>
            <span v-else> &laquo;</span>
          </li>
          <li v-for="(n,key) in totalPage" :key="key"><a @click="getMyArticle(n)">{{ n }}</a></li>
          <li>
            <a  @click="getMyArticle(currentPage+1)"  v-if="currentPage!=totalPage" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
            </a>
            <span v-else>&raquo;</span>
          </li>
        </ul>
      </nav>
    </div>
</template>

<script>
  import requests, { setAccessToken } from '@/api/requests.js'
  import { getCookie } from "../../../util";
  import Item from './Item'
  export default {
    name: "userArticle",
    components:{
      Item
    },
    data() {
      return {
        articles: {},
        page: 1,
        totalPage: 1,
        currentPage: 1,
        comments: [],
        commentOn: 0,
      }
    },
    props:{
      ifMe: Boolean
    },
    methods:{
      async getMyArticle(page){
        this.currentPage = page
        let id = this.$route.params.id
        console.log(id)
        let perpage = 3 //每页显示的文章数目
        let url = ''
        if(this.ifMe) url = '/articles?per_page='+perpage+'&page='+page
        else url = '/articles?per_page='+perpage+'&page='+page + '&author_id='+id
        try {
          console.log(url)
          let data =  await requests.get(url)
          this.articles = data.articles
          this.totalPage = Math.ceil(data.pagination.total / perpage)
        }catch(e) {
          console.log(e || 'unknown mistake' )
        }
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
