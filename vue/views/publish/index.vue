<template>
    <div>
        <h1>发表文章页面</h1>
        <div class="editor-container col-md-6 ">
          <input type="text" class="input-lg" v-model="title" >
          <editor class="editor" :value="content"  :setting="editorSetting" @input="(content)=> this.content = content"></editor>
        </div>
        <div class="col-md-4">
          <div>
            <form role="form">
              <div class="form-group">
                <select class="form-control"  v-model="selectedParent">
                  <option  v-if="!kind.parent_id" v-for="kind in kinds" v-bind:value="kind.id">
                    {{ kind.name }}
                  </option>
                </select>
                <select class="form-control" v-model="category">
                  <option  v-if="kind.parent_id==selectedParent" v-for="kind in kinds" v-bind:value="kind.id">
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
          <button @click="publishIt">发表文章</button>
        </div>
    </div>
</template>

<script>
    import editor from '@/components/editor'
    import request, { setAccessToken } from '../../api/requests'
    import { getCookie } from "../../util";
    export default {
        components: {
            'editor': editor
        },
        name: "Publish",
        data: function () {
          return {
              content:'你好，在这里写下文章内容',
              //tinymce的配置信息
              editorSetting:{
                  height:400,
              },
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

</style>
