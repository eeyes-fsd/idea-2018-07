<template>
    <div>
      <ol class="breadcrumb">
        <li><router-link :to="`/category/${article.category.parent.id}`">{{ article.category.parent.name }}</router-link></li>
        <li><router-link :to="`/category/${article.category.id}`">{{ article.category.name }}</router-link></li>
        <li><router-link  :to="`/article/${article.id}`">{{ article.title }}</router-link></li>
      </ol>
      <div>
        <div class="row">
          <div class="col-md-7">
            <div class="panel panel-default article-content">
              <h1>{{ article.title }}</h1>
              <hr style="border-top: 2px solid #000; " />
              <div v-html="article.body" class="article"></div>
            </div>
            <div class="reply  panel panel-default">
              <div v-if="this.isLogin" class="reply-input">
                <div>
                  <tinymce :height="300" v-model="content"/>
                   <button class="btn btn-primary pull-right commentBtn" @click="toComment">发表评论</button>
                </div>
                <hr style="border-top:2px solid #000; clear:both;" />
              </div>
              <div v-else>
                <p>登录后才可以评论</p>
                <hr style="border-top:2px solid #000;" />
              </div>
              <comment v-for="(comment,key) in comments" :key=key :comment="comment"></comment>
            </div>
          </div>
          <div class="col-md-4 panel panel-default author-info">
            <router-link :to="`/user/${author.id}/article`">
              <img :src="author.avatar" alt="head" class="author-head img-circle">
            </router-link>
            <router-link :to="`/user/${author.id}/article`">
              <h4>{{ author.nickname }}</h4>
            </router-link>
            <p>{{ author.signature }}</p>
            <p>
              <span class="glyphicon glyphicon-eye-open" >&emsp;{{ article.view_count }}&emsp;&emsp;</span>
              <a  href="javascript:;"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true" @click="likeIt()" :class="{ like : article.liked }">&emsp;{{ article.like_count }}&emsp;&emsp;</span></a>
              <a  href="javascript:;"><span class="glyphicon glyphicon-heart" aria-hidden="true" @click="collectIt()"  :class="{ like : article.favorited }">&emsp;</span></a>
            </p>
            <p>{{ article.created_at }}</p>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import { mapState, mapMutations } from 'vuex'
  import request from '../../api/requests'
  import editor from '@/components/editor'
  import comment from './comment'
  import tinymce from '@/components/Tinymce'

  export default {
      components:{
          editor,
          comment,
          tinymce,
      },
      name: "Article",
      data (){
          return {
            article: {},
            articleId: -1,
            author: {},
            //tinymce的配置信息
            editorSetting:{
              height:100,
            },
            content: '发布你的神评论吧',
            comments:{},
          }
      },
      methods:{
        async getArticle(){
          try {
            let data = await request.get('/articles/'+ this.$route.params.id)
            this.article = data
            this.articleId = data.id
            this.author = data.author
          }catch (e) {
            console.log(e || 'unknown mistake')
          }
        },
        async getComments(){
          try {
            let data = await request.get('/replies?article_id='+this.$route.params.id)
            this.comments = data.replies
          }catch (e) {
            console.log(e || 'unknown mistake' )
          }
        },
        getLogin(){
          return this.$store.state.isLogin
        },
        async toComment(){
          try {
            let data = await request.post('/replies', { article_id: this.$route.params.id, body: this.content})
            location.reload()
          } catch (err) {
            console.log(this.errorMessage = err.message || '未知错误')
          }
        },
        async likeIt () {
          if(!this.isLogin){
            alert("登陆后才可以评论噢~")
            return
          }
          try{
            let data = await request.post('/likes',{ article_id : this.articleId })
            if (data[0]==='点赞成功'){
              this.article.like_count++
              this.article.liked=1
            }else if(data[0]==='取消点赞成功') {
              this.article.like_count--
              this.article.liked=0
            }
          }catch (e) {
            console.log(this.errorMessage = err.message || '未知错误')
          }
        },
        async collectIt () {
          if(!this.isLogin){
            alert("登陆后才可以收藏噢~")
            return
          }
          try{
            let data = await request.post('/favorites',{ article_id : this.articleId })
            if (data[0]==='收藏成功'){
              this.article.favorited = 1
            }else if(data[0]==='取消收藏成功') {
              this.article.favorited = 0
            }
          }catch (e) {
            console.log(this.errorMessage = err.message || '未知错误')
          }
        },
      },
      mounted (){
        this.getArticle()
        this.getComments()
      },
      computed: {
        ...mapState({
        isLogin: 'isLogin'
        })
      },
    }
</script>

<style>
  .wscnph{
    max-width: 300px;
  }
  @media screen and (max-width: 400px) {
    .wscnph{
      max-width: 200px;
    }
  }
</style>


<style scoped lang="scss">
  .author-head{
    width: 50px;
    height: 50px;
    float: left;
    margin-right: 2rem;
  }
  .article-content{
    padding: 0 3em;
  }
  .reply-input{
    padding: 1em 2em;
    div{
      overflow: hidden;
    }
  }
  .commentBtn{
    margin: 2em;
  }
  .author-info{
    padding: 2em;
  }
  .like{
    color: red;
  }
</style>
