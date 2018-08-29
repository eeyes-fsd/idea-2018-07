<template>
  <div v-loading="loading">
    <div class="article">
    <div class="article-header">
      <img :src="article.author.avatar" alt="头像" class="img-circle pull-left article-head">
      <h4><a :href="'/#/article/'+article.id">{{ article.title }}</a></h4>
      <p class="article-class">{{ article.updated_at }}
        <router-link :to="`/category/${article.category.id}`">
          <span class="pull-right">{{ article.category.name }}</span>
        </router-link>
        <router-link :to="`/category/${article.category.parent.id}`">
          <span class="pull-right">{{ article.category.parent.name }}</span>
        </router-link>
      </p>
    </div>
    <div class="article-content" v-html="article.body"></div>
    <div class="article-footer">
      <p>
        <strong>浏览：</strong>{{ article.view_count }}
        <a class="pull-right" @click="tryDelete()" :id="article.id" href="javascript:;">
          <span class="glyphicon glyphicon-trash">&emsp;&emsp;</span>
        </a>
        <a class ="pull-right" @click="showcomment()" :id="article.id" href="javascript:;">
          <span class="glyphicon glyphicon-comment" aria-hidden="true">{{ article.reply_count }}&emsp;</span>
        </a>
        <a class="pull-right" @click="likeArticle()" :id="article.id"  href="javascript:;">
          <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"  :class="{ like : article.liked }">{{ article.like_count }}&emsp;&emsp;</span>
        </a>
      </p>
    </div>
    <Dialog :visible.sync="editing" @confirm="submit" position="center">
      <h2 class="text-center">确认删除本篇文章？</h2>
    </Dialog>
    <div class="col-md-12" v-if="showInput&&this.isLogin">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="发表你的神评论" v-model="commentCon">
        <span class="input-group-btn">
          <button class="btn btn-default" :id="article.id" type="button" @click="toCommentArticle()">评论</button>
        </span>
      </div>
    </div>
    <hr/>
    <div class="article-comment row">
      <comment v-if="comments.length > 0" :comment="comments[0]"></comment>
    </div>
    </div>
    <hr style="border-top:2px solid #655e5e; width:90%;" />
  </div>
</template>

<script>
import requests from "@/api/requests.js";
import comment from "@/views/article/comment";
import Dialog from "@/components/Dialog";
import { mapState, mapMutations } from 'vuex';
export default {
  name: "Item",
  props: {
    article: Object
  },
  data() {
    return {
      comments: {},
      showInput: false,
      commentCon: "",
      editing: false,
      loading: false,
    };
  },
  components: {
    comment,
    Dialog
  },
  methods: {
    async getComment() {
      //获取评论
      this.loading = true
      let id = this.article.id;
      try {
        let data = await requests.get("/replies?article_id=" + id);
        this.comments = data.replies;
        this.loading = false
      } catch (e) {
        console.log(e || "unknown mistake");
      }
    },
    async likeArticle() {
      //点赞文章
      try {
        this.loading = true
        let id = event.currentTarget.getAttribute("id");
        let data = await requests.post("/likes", { article_id: id });
        if (data[0] === "点赞成功") {
          this.article.like_count++;
          this.article.liked = 1
        } else if (data[0] === "取消点赞成功") {
          this.article.liked = 0
          this.article.like_count--;
        }
        this.loading = false
      } catch (e) {
        console.log(e || "unknown mistake");
      }
    },
    showcomment() {
      //显示评论框
      this.showInput = !this.showInput;
    },
    tryDelete() {
      //删除文章
      this.editing = true;
    },
    async submit() {
      try {
        let id = this.article.id;
        let data = await requests.delete("/articles/" + id);
        if (data === null) {
          alert("删除成功");
          location.reload();
        }
      } catch (e) {
        console.log(e || "unknown miskake");
      }
    },
    async toCommentArticle() {
      //评论文章
      try {
        this.loading = true
        let id = event.currentTarget.getAttribute("id");
        let data = await requests.post("/replies", {
          body: this.commentCon,
          article_id: id,
        });
        if (data != null) {
          let data1 = await requests.get(
            "/replies?article_id=" + data.article_id
          );
          this.comments = data1.replies;
        }
        this.commentCon = ''
        this.showInput = false
        this.loading = false
      } catch (e) {
        console.log(e || "unknown miskake");
      }
    }
  },
  mounted() {
    this.getComment();
  },
  computed: {
    ...mapState({
      isLogin: 'isLogin'
    })
  },
};
</script>

<style>
.article-content img {
  max-width: 300px;
}
@media screen and (max-width: 400px) {
  .article-content img {
    max-width: 200px;
  }
}
</style>


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
  padding: 0 32px;
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
.like{
  color: red;
}
</style>
