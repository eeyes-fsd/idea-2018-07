<template>
  <div>
    <div>
      <img :src="comment.author.avatar" alt="head" class="author-head pull-left">
      <p>{{ comment.author.nickname }}</p>
      <p>{{ comment.created_at }}</p>
    </div>
    <div  v-html="comment.body"></div>
    <hr/>
  </div>
</template>

<script>
export default {
  name:'comment',
  data () {
    return {

    }
  },
  props: {
    comment: Object
  },
  methods: {

  },
  mounted() {

  }
}
</script>

<style>
  .author-head{
    width: 50px;
    height: 50px;
    float: left;
    margin-right: 2rem;
  }
</style>
