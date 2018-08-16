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
                <select class="form-control " >
                  <option value="kind.id" v-for="kind in kinds1">
                    {{ kind.name }}
                  </option>
                </select>
                <select class="form-control ">
                  <option value="kind.id" v-for="kind in kinds2">
                    {{ kind.name }}
                  </option>
                </select>
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
              kinds1:{},
              kinds2:{}
          }
        },
        methods: {
          async publishIt () {
            try {
              setAccessToken(getCookie('access_token'))
              let data = await request.post('/articles', { title: this.title, body: this.content })
              console.log(data)
            } catch (err) {
              console.log(err)
              console.log(this.errorMessage = err.message || '未知错误')
            }
          },
          getKinds () {
            this.kinds1 = {}
          }
        },
      mounted: {

      }

    }
</script>

<style scoped>

</style>
