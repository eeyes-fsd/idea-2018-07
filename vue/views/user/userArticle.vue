<template>
    <div>
      <div v-for="(article,index) in articles" class="article" :key="index">
        <div class="article-header">
          <img src="article.author.avatar" alt="头像" class="img-circle pull-left article-head">
          <h4><a :href="'/#/articles/'+article.id">{{ article.title }}</a></h4>
          <p>{{ article.updated_at }} <span class="pull-right">分类</span></p>
        </div>
        <div class="article-content" v-html="article.body"></div>
        <div class="article-footer">
          <p>
            <strong>浏览：</strong>{{ article.view_count }}
            <a class ="pull-right" @click="tocomment(article.id)"  :id="article.id"><span class="glyphicon glyphicon-comment" aria-hidden="true">{{ article.reply_count }}&emsp;</span></a>
            <a class="pull-right" @click="likeArticle()"  :id="article.id"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true">{{ article.like_count }}&emsp;&emsp;&emsp;</span></a>
          </p>
        </div>
        <hr/>
        <div class="article-comment row">
          <div class="col-md-12" v-for="(comment,key) in comments" v-if="comment[0].id===article.id" :key=key>
            <div>
              <img :src="comment[0].author.avatar" class="img-circle pull-left article-head" alt="头像">
              <p>{{ comment[0].author.nickname }}</p>
              <p>{{ comment[0].created_at }}</p>
            </div>
            <div  v-html="comment[0].body">
            </div>
            <hr/>
          </div>
          <div class="col-md-12" v-if="commentOn===article.id">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search for...">
              <span class="input-group-btn">
                <button class="btn btn-default" type="button">评论</button>
              </span>
            </div>
          </div>
        </div>
      </div>
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
  import { getCookie } from "../../util";
  export default {
    name: "userArticle",
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
    methods:{
      async getMyArticle(page){
        this.currentPage = page
        let id = JSON.parse(getCookie('userInfo')).id
        let perpage = 3 //每页显示的文章数目
        try {
          let data =  await requests.get('/articles?per_page='+perpage+'&page='+page)
          this.articles = data.articles
          this.totalPage = Math.ceil(data.pagination.total / perpage)
          let ids = []  //获取文章id，后根据id获取评论
          for(let item of data.articles){
            ids.push(item.id);
          }
          Vue.set(this.articles,"comment",[])
          for(let i=0; i<perpage ;i++){
            let comment = await requests.get('/replies?article_id='+ids[i])
            this.comments.push(comment) 
          }    
        }catch(e) {
          console.log(e || 'unknown mistake' )
        }
      },
      tocomment (id){
        if(id === this.commentOn){
          this.commentOn = 0
        }else{
          this.commentOn = id
        }
      },
      async likeArticle(){  //给文章点赞
        try{
          let id = event.currentTarget.getAttribute("id")
          setAccessToken(getCookie('access_token'))
          let data = await requests.post('/likes',{ article_id: id })
          console.log(data)
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
      this.getMyArticle(1)//默认获取第一页的文章
    }
  }
</script>

<style scoped lang="scss">
  .article-head{
    width: 60px;
    height: 60px;
  }
  .article-comment{
    padding: 32px;
    .comment-head{
      width: 48px;
      height: 48px;
    }
  }
  .article-footer>p{
    padding-right: 1rem;
  }
</style>
