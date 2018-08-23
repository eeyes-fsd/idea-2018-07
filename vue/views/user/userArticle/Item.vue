<template>
  <div>
    <div class="article">
    <div class="article-header">
      <img :src="article.author.avatar" alt="头像" class="img-circle pull-left article-head">
      <h4><a :href="'/#/article/'+article.id">{{ article.title }}</a></h4>
      <p class="article-class">{{ article.updated_at }} <span class="pull-right">{{ article.category.name }}</span></p>
    </div>
    <div class="article-content" v-html="article.body"></div>
    <div class="article-footer">
      <p>
        <strong>浏览：</strong>{{ article.view_count }}
        <a class="pull-right" @click="tryDelete()" :id="article.id"><span class="glyphicon glyphicon-trash">&emsp;&emsp;</span></a>
        <a class ="pull-right" @click="showcomment()"  :id="article.id"><span class="glyphicon glyphicon-comment" aria-hidden="true">{{ article.reply_count }}&emsp;</span></a>
        <a class="pull-right" @click="likeArticle()"  :id="article.id"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true">{{ article.like_count }}&emsp;&emsp;</span></a>
      </p>
    </div>
    <Dialog :visible.sync="editing" @confirm="submit" position="center">
      <h2 class="text-center">确认删除本篇文章？</h2>
    </Dialog>
    <hr/>
    <div class="article-comment row">
      <comment :comment="comments[0]"></comment>
    </div>
    </div>
  </div>
</template>

<script>
import requests from '@/api/requests.js'
import comment from '@/views/article/comment'
import Dialog from '@/components/Dialog'
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
      editing: false,
    }
  },
  components:{
    comment,
    Dialog,
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
    tryDelete () { //删除文章
      this.editing = true
    },
    async submit () {
      try{
        let id = this.article.id
        let data = await requests.delete('/articles/'+id)
        if(data===null){
          alert("删除成功")
          location.reload()
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

<style>
  .article-content img{
    max-width: 300px;
  }
</style>


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
