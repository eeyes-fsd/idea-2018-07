<template>
  <div>
    <div class="article">
      <div class="article-header">
        <img :src="article.author.avatar" alt="头像" class="img-circle pull-left article-head">
        <h4><a :href="'/#/article/'+article.id">{{ article.title }}</a></h4>
        <p class="article-class">{{ article.updated_at }}
          <router-link :to="`/category/${article.category.id}`"><span class="pull-right">{{ article.category.name }}</span>
          <router-link :to="`/category/${article.category.parent.id}`"><span class="pull-right">{{ article.category.parent.name }}</span></router-link></router-link>
        </p>
      </div>
      <div class="article-content" v-html="article.body"></div>
      <div class="article-footer">
        <p>
            <strong>浏览：</strong>{{ article.view_count }}
            <span class="glyphicon glyphicon-comment pull-right" aria-hidden="true">{{ article.reply_count }}&emsp;</span>
            <span class="glyphicon glyphicon-thumbs-up pull-right" aria-hidden="true">{{ article.like_count }}&emsp;&emsp;</span>
            <button type="button" @click="tryDislike()" class="btn btn-danger pull-right">取消收藏</button>
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
import requests from '@/api/requests.js'
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
.article-header {
  height: 7em;
}
.article-head {
  width: 60px;
  height: 60px;
  margin: 0 24px;
}
.article-comment {
  padding: 32px;
  .comment-head {
    width: 48px;
    height: 48px;
  }
}
.article-content {
  padding: 0 2em;
}
.article-class {
  padding-right: 6em;
  span {
    border: 1px solid #000;
    padding: 2px;
    margin-left: 1em;
    color: #000;
  }
}
.article-footer>p>button{
  margin-right: 2em;
  margin-top: -0.7em;
}
</style>
