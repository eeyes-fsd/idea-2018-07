<template>
  <div class="row comment">
    <ul class="media-list">
      <li class="media">
        <div class="media-left">
          <router-link :to="'/user/'+comment.author.id+'/article'">
            <img :src="comment.author.avatar" alt="头像" class="comment-head img-circle">
          </router-link>
        </div>
        <div class="media-body">
          <h4>{{ comment.author.nickname }}</h4>
          <p>{{ comment.created_at }}</p>
          <p v-html="comment.body"></p>
          <p>
            <a class="pull-left" @click="likeComment()"  :id="comment.id" href="javascript:;"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true" :class="{ like : comment.liked }">{{ comment.like_count }}&emsp;&emsp;</span></a>
            <a class ="pull-left" @click="showcomment()"  :id="comment.id" href="javascript:;"><span class="glyphicon glyphicon-comment" aria-hidden="true">{{ comment.reply_count }}&emsp;</span></a>
          </p>

          <div class="media" v-for="(item,key) in comment.children" :key="key">
            <div class="media-left">
            </div>
            <div class="media-body second-com">
              <p>
                <router-link :to="'/user/'+item.author.id+'/article'"><span>{{ item.author.nickname }}</span></router-link>:
                <span v-html="item.body"></span>
              </p>
              <p>
                {{ item.created_at }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a  @click="likeSecondComment()" :index="key" :id="item.id"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true" >{{ item.like_count }}&emsp;&emsp;</span></a>
                <a  @click="showcomment()"  :id="comment.id"><span class="glyphicon glyphicon-comment" aria-hidden="true">{{ comment.reply_count }}&emsp;</span></a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-12" v-if="showInput&&this.isLogin">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="发表你的神评论" v-model="commentCon">
            <span class="input-group-btn">
              <button class="btn btn-default" :id="comment.id" type="button" @click="commentComments">评论</button>
            </span>
          </div>
        </div>
      </li>
    </ul>
    <hr style="border-top:1px solid #000; clear:both;" />
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import requests from '@/api/requests'
export default {
  name:'comment',
  data () {
    return {
      showInput: false,
      commentCon: '',
    }
  },
  props: {
    comment: Object
  },
  methods: {
    showcomment () {
      this.showInput = !this.showInput
    },
    async likeComment () {  //给评论点赞
      let id = event.currentTarget.getAttribute("id")
      try{
        let data = await requests.post('/likes',{ reply_id : id })
        if(data[0]==='点赞成功'){
          this.comment.like_count++
          this.comment.liked = 1
        }else if(data[0]==='取消点赞成功'){
          this.comment.like_count--
          this.comment.liked = 0
        }
      }catch (e) {
        console.log(e || 'unknown mistake')
      }
    },
    async commentComments () {  //评论评论，二级评论
      let id = event.currentTarget.getAttribute("id")
      console.log(this.commentCon)
      try{
        let data = await requests.post('/replies', { body: this.commentCon, reply_id: id })
        location.reload()
      }catch (e){
        console.log(e || 'unknown mistake')
      }
    },
    async likeSecondComment () {  //二级评论点赞
      let id = event.currentTarget.getAttribute("id")
      let index = event.currentTarget.getAttribute("index")
      console.log(index,id)
      try{
        let data = await requests.post('/likes',{ reply_id : id })
        if(data[0]==='点赞成功'){
          this.comment.children[index].like_count++
        }else{
          this.comment.children[index].like_count--
        }
      }catch (e) {
        console.log(e || 'unknown mistake')
      }
    }
  },
  computed: {
    ...mapState({
      isLogin: 'isLogin'
    })
  },
}
</script>

<style>
  .comment-head{
    width: 50px;
    height: 50px;
    margin-right: 2rem;
  }
  .comment{
    margin: 10px 0;
    padding: 12px;
  }
  .media{
    clear: both;
  }
  .second-con>a>h4{
    margin: 0;
  }
  .like{
    color: red;
  }
</style>
