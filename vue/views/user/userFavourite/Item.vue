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
            <span class="glyphicon glyphicon-comment pull-right" aria-hidden="true">{{ article.reply_count }}&emsp;</span>
            <span class="glyphicon glyphicon-thumbs-up pull-right" aria-hidden="true">{{ article.like_count }}&emsp;&emsp;</span>
            <a class="pull-right" @click="tryDislike()" :id="article.id"><span>取消收藏</span></a>
        </p>
      </div>
      <Dialog :visible.sync="editing" @confirm="submit" position="center">
        <h2 class="text-center">确认取消收藏本篇文章？</h2>
      </Dialog>
      <hr/>
    </div>
  </div>
</template>

<script>
import requests, { setAccessToken } from '@/api/requests.js'
import Dialog from '@/components/Dialog'
export default {
  name: 'Item',
  props: {
    article: Object
  },
  data () {
    return {
      editing: false
    }
  },
  components: {
    Dialog
  },
  methods : {
    tryDislike () {
      this.editing = true
    },
    async submit () {
      try{
        let data = await requests.post('/favorites',{ article_id : this.article.id })
        if(data[0]==='取消收藏成功'){
          alert("取消收藏成功")
          location.reload()
        }
      }catch (e) {
        console.log(e || 'unknown mistake')
      }
    }
  },
}
</script>

<style scope lang="scss">
  .article-head{
    width: 60px;
    height: 60px;
  }
  .article-class{
    padding-right: 6em;
    span{
      border: 1px solid #000;
      padding: 2px;
    }
  }
</style>
