<template>
    <div>
        <h1>发表文章页面</h1>
        <div class="editor-container">
            <editor class="editor" :value="content"  :setting="editorSetting" @input="(content)=> content = content"></editor>
        </div>
    </div>
</template>

<script>
    import editor from '@/components/editor'
    export default {
        components: {
            'editor': editor
        },
        name: "Publish",
        data: function () {
          return {
              content:'你好，在这里写下文章内容',
              //tinymce的配置信息 参考官方文档 https://www.tinymce.com/docs/configure/integration-and-setup/
              editorSetting:{
                  height:400,
              }

          }
        },
        methods: {

        }

    }
</script>

<style scoped>

</style>