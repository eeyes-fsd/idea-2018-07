<template>
    <div>
        <div class="editor-body col-md-8 panel panel-default">
          <div class="form-group">
            <label for="title">标题</label>
            <input type="text" class="form-control" v-model="title" placeholder="请输入标题">
          </div>
          <div class="editor">
            <tinymce :height="300" allowPicture="true" v-model="content"/>
          </div>
        </div>
        <div class="col-md-3 panel panel-default settings">
          <div>
            <form role="form">
              <div class="form-group">
                <select class="form-control cate"  v-model="selectedParent">
                  <option  v-if="!kind.parent_id" v-for="(kind,key) in kinds" v-bind:value="kind.id" :key='key'>
                    {{ kind.name }}
                  </option>
                </select>
                <select class="form-control cate" v-model="category">
                  <option  v-if="kind.parent_id==selectedParent" v-for="(kind,key) in kinds" v-bind:value="kind.id" :key='key'>
                    {{ kind.name }}
                  </option>
                </select>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" v-model="anonymous">匿名
                  </label>
                </div>
              </div>
            </form>
          </div>
          <button @click="publishIt" class="btn btn-primary center-block">发表文章</button>
        </div>
    </div>
</template>

<script>
import editor from '@/components/editor'
import request from '../../api/requests'
import tinymce from '@/components/Tinymce'

export default {
    components: {
      editor,
      tinymce
    },
    name: "Publish",
    data: function () {
      return {
          content:
            `<h1 style="text-align: center;">欢迎在创意工坊创出你的一片天地！</h1>`,
          title:'',
          body:'',
          kinds:{},
          selectedParent: 1,  //初始化默认的分类
          category: 6,
          anonymous: false
      }
    },
    methods: {
      async publishIt () {
        if(this.title===''){
          alert('请输入标题')
          return
        }
        try {
          let data = await request.post('/articles', { title: this.title, body: this.content, category_id: this.category, anonymous: this.anonymous })
          alert("发表文章成功！")
          this.$router.push('/article/'+data.id)
        } catch (err) {
          console.log(this.errorMessage = err.message || '未知错误')
        }
      },
      async getKinds (){
        let that = this
        try {
          let data = await request.get('/categories')
          that.kinds = data
        }catch (e) {
          console.log(e || 'unknown mistake')
        }
      }
    },
  mounted() {
    this.getKinds()
  }
}
</script>

<style scoped>
  .editor{
    overflow: hidden;
  }
  .editor-body{
    padding: 3em;
  }
  .settings{
    padding: 2em;
    margin: 0 2em;
  }
  @media screen and (max-width: 400px) {
    .settings {
    margin: 0;
    }
  }
  .cate{
    margin: 2em 0;
  }
</style>
