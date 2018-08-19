<template>
    <div>
      <div>
        <div class="row">
          <div class="col-md-7">
            <div class="article panel panel-default">
              <h1>{{ article.title }}</h1>
              <div v-html="article.body"></div>
            </div>
            <div class="reply  panel panel-default">
              <div v-if="getLogin">
                <editor class="editor" :value="content"  :setting="editorSetting" @input="(content)=> this.content = content"></editor>
                <button @click="toComment">发表评论</button>
              </div>
              <div v-else>
                登录后才可以评论
              </div>
              <div>
                <div v-for="comment in comments" >
                  <div>
                    <img :src="comment.author.anatar" alt="head">
                    <p>{{ comment.author.nickname }}</p>
                    <p>{{ comment.created_at }}</p>
                  </div>
                  <div  v-html="comment.body">
                  </div>
                  <hr/>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4  panel panel-default">
            <img src="" alt="head" class="author-head img-round">
            <p>{{ author.nickname }}</p>
            <p>{{ author.signature }}</p>
            <p>
              <span class="glyphicon glyphicon-eye-open">&emsp;{{ article.view_count }}&emsp;&emsp;</span>
              <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true">&emsp;{{ article.like_count }}</span>
            </p>
            <p>{{ article.created_at }}</p>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import request, { setAccessToken } from '../../api/requests'
  import editor from '@/components/editor'
  import { getCookie } from "../../util";

  export default {
      components:{
          editor
      },
      name: "Article",
      data (){
          return {
            article: {},
            author: {},
            //tinymce的配置信息
            editorSetting:{
              height:100,
            },
            content: '发布你的神评论吧',
            comments:{}
          }
      },
      methods:{
        async getArticle(){
          try {
            let data = await request.get('/articles/'+ this.$route.params.id)
            this.article = data
            this.author = data.author
          }catch (e) {
            console.log(e || 'unknown mistake')
          }
        },
        async getComments(){
          try {
            let data = await request.get('/replies?article_id='+this.$route.params.id)
            this.comments = data
          }catch (e) {
            console.log(e || 'unknown mistake' )
          }
        },
        getLogin(){
          return this.$store.state.ifLogin
        },
        async toComment(){
          try {
            setAccessToken(getCookie('access_token'))
            let data = await request.post('/replies', { article_id: this.$route.params.id, body: this.content})
            location.reload()
          } catch (err) {
            console.log(this.errorMessage = err.message || '未知错误')
          }
        }
      },
      mounted (){
        this.getArticle()
        this.getComments()
      },
    }
</script>

<style scoped>
  .author-head{
    width: 50px;
    height: 50px;
    float: left;
    margin-right: 2rem;
  }
</style>
