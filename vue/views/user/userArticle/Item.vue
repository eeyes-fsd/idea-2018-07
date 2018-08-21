<template>
  <div>
    <div class="article">
    <div class="article-header">
      <img src="article.author.avatar" alt="头像" class="img-circle pull-left article-head">
      <h4><a :href="'/#/article/'+article.id">{{ article.title }}</a></h4>
      <p class="article-class">{{ article.updated_at }} <span class="pull-right">{{ article.category.name }}</span></p>
    </div>
    <div class="article-content" v-html="article.body"></div>
    <div class="article-footer">
      <p>
        <strong>浏览：</strong>{{ article.view_count }}
        <a class="pull-right" @click="deleteIt()" :id="article.id"><span class="glyphicon glyphicon-trash">&emsp;&emsp;</span></a>
        <a class ="pull-right" @click="showcomment()"  :id="article.id"><span class="glyphicon glyphicon-comment" aria-hidden="true">{{ article.reply_count }}&emsp;</span></a>
        <a class="pull-right" @click="likeArticle()"  :id="article.id"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true">{{ article.like_count }}&emsp;&emsp;</span></a>
      </p>
    </div>
    <hr/>
    <div class="article-comment row">
      <div v-for="(comment,key) in comments" v-if="key<2" :key=key>
        <div>
          <img :src="comment.author.avatar" class="img-circle pull-left article-head" alt="头像">
          <p>{{ comment.author.nickname }}</p>
          <p>{{ comment.created_at }}</p>
        </div>
        <div  v-html="comment.body"></div>
      <hr/>
      </div>
      <div class="col-md-12" v-if="showInput">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="发表你的神评论" v-model="commentCon">
          <span class="input-group-btn">
          <button class="btn btn-default" :id="article.id" type="button" @click="toCommentArticle">评论</button>
          </span>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
import requests, { setAccessToken } from '@/api/requests.js'
export default {
  name: 'Item',
  props: {
    article: Object
  },
  data () {
    return {
      comments: {},
      showInput: false,
      commentCon: '',
    }
  },
  methods : {
    async getComment () { //获取评论
      let id = this.article.id
      try {
        let data = await requests.get('/replies?article_id='+id)
        this.comments = data.replies
      }catch (e) {
        console.log(e || 'unknown mistake')
      }

    },
    async likeArticle () { //点赞文章
        try{
          let id = event.currentTarget.getAttribute("id")
          let data = await requests.post('/likes',{ article_id: id })
          if (data[0]==='点赞成功'){
            this.article.like_count++
          }else if(data[0]==='取消点赞成功') {
            this.article.like_count--
          }
        }catch(e){
          console.log(e || 'unknown mistake')
        }
    },
    showcomment () {  //显示评论框
      this.showInput = !this.showInput
    },
    async deleteIt () { //删除文章
      try{
        let id = event.currentTarget.getAttribute("id")
        try{
          let data = await requests.delete('/articles/'+id)
          console.log(data)
        }catch (e){
          console.log(e || 'unknown mistake')
        }
      }catch(e) {
        console.log(e || 'unknown miskake')
      }
    },
    async toCommentArticle () { //评论文章
      try{
        let id = event.currentTarget.getAttribute("id")
        let data = await requests.post('/replies',{ body : this.commentCon, article_id : id })
        if(data != null){
          let data1 = await requests.get('/replies?article_id='+data.article_id)
          this.comments = data1.replies
          console.log(data1)
        }
      }catch(e) {
        console.log(e || 'unknown miskake')
      }
    }
  },
  mounted () {
    this.getComment()
  }
}
</script>

<style scope lang="scss">
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
  .article-class{
    padding-right: 6em;
    span{
      border: 1px solid #000;
      padding: 2px;
    }
  }
</style>
