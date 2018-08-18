<template>
    <div>
      <p>文章详细内容</p>
      <div>{{ $route.params.id }}</div>
      <div>
        <h1>{{ title }}</h1>
        <div v-html="content"></div>
      </div>
    </div>
</template>

<script>
  import request from '../../api/requests'
    export default {
        components:{},
        name: "Article",
        data (){
          return {
            title: '',
            content: ''
          }
        },
        methods:{
          async getArticle(){
            try {
              let data = await request.get('/articles/'+ this.$route.params.id)
              this.title = data.title
              this.content = data.body
              console.log(data)
            }catch (e) {
              console.log(e || 'unknown mistake')
            }
          }
        },
        mounted (){
          this.getArticle()
        }

    }
</script>

<style scoped>

</style>
