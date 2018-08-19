<template>
    <div>
      <div v-for="(article,index) in articles" class="article" :index="index">
        <div class="article-header">
          <img src="article.author.avatar" alt="头像" class="img-circle pull-left article-head">
          <h4><a :href="'/#/articles/'+article.id">{{ article.title }}</a></h4>
          <p>{{ article.updated_at }} <span class="pull-right">分类</span></p>
        </div>
        <div class="article-content" v-html="article.body"></div>
        <div class="article-footer">
          <p>
            <strong>浏览：</strong>{{ article.view_count }}
            <a class ="pull-right" @click="tocomment"  :id="article.id"><span class="glyphicon glyphicon-comment" aria-hidden="true">{{ article.reply_count }}&emsp;</span></a>
            <a class="pull-right" @click="likeArticle()"  :id="article.id"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true">{{ article.like_count }}&emsp;&emsp;&emsp;</span></a>
          </p>
        </div>
        <hr/>
      </div>
    </div>
</template>

<script>
  import requests, { setAccessToken } from '@/api/requests.js'
  import { getCookie } from "../../util";
  export default {
    name: "userArticle",
    data() {
      return {
        articles: {}
      }
    },
    methods:{
      async getMyArticle(){
        let id = JSON.parse(getCookie('userInfo')).id
        try {
          let data =  await requests.get('/articles?page=1')
          this.articles = data.articles
        }catch (e) {
          console.log(e || 'unknown mistake' )
        }
      },
      tocomment (){

      },
      async likeArticle(){
        try{
          let id = event.currentTarget.getAttribute("id")
          setAccessToken(getCookie('access_token'))
          let data = await requests.post('/likes',{ article_id: id })
          if (data[0]==='点赞成功'){
            location.reload()
          }else if(data[0]==='取消点赞成功') {
            location.reload()
          }
        }catch(e){
          console.log(e || 'unknown mistake')
        }
      }
    },
    mounted(){
      this.getMyArticle()
    }
  }
</script>

<style scoped>
  .article-head{
    float: left;
    width: 60px;
    height: 60px;
  }
  .article-footer>p{
    padding-right: 1rem;
  }
</style>
