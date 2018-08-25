<template>
  <el-upload
    class="co-image-uploader"
    :action="action"
    ref="uploader"
    :name="name"
    :headers="headers"
    :show-file-list="false"
    :auto-upload="false"
    :before-upload="beforeUpload"
    :on-success="handleSuccess"
    :on-error="handleError">
    <img v-if="imageUrl" :src="imageUrl" class="image">
    <i v-else class="el-icon-plus co-image-uploader-icon"></i>
  </el-upload>
</template>

<script>
import { mapState } from 'vuex'

export default {
  name: 'CoImageUploader',
  props: {
    action: String,
    name: String
  },
  data () {
    return {
      imageUrl: ''
    }
  },
  mounted () {
    this._input.onchange = this.handleChooseFile
  },
  methods: {
    handleChooseFile (event) {
      let file = event.srcElement.files[0]
      this.imageUrl = URL.createObjectURL(file)
      this.$emit('choose', file)
    },
    upload () {
      this.$refs.uploader.submit()
    },
    beforeUpload (file) {
      var isJPG = file.type === 'image/jpeg';
      var isLt2M = file.size / 1024 / 1024 < 2;
      if (!isJPG) {
        this.$message.error('上传头像图片只能是 JPG 格式!');
      }
      if (!isLt2M) {
        this.$message.error('上传头像图片大小不能超过 2MB!');
      }
      return isJPG && isLt2M;
    },
    handleSuccess (res) {
      this.$emit('success', res)
    },
    handleError (err) {
      this.$emit('error', err)
    }
  },
  computed: {
    ...mapState({
      accessToken: 'accessToken'
    }),
    headers () {
      return {
        Authorization: `Bearer ${this.accessToken}`
      }
    },
    _el () {
      return this.$refs.uploader.$el
    },
    _input () {
      return this._el.children[0].children[1]
    },
  }
}
</script>

<style>
.co-image-uploader {
  text-align: center;
}
.co-image-uploader .el-upload {
  border: 1px dashed #d9d9d9;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.co-image-uploader .el-upload:hover {
  border-color: #409EFF;
}
.co-image-uploader input {
  display: none;
}
.co-image-uploader-icon {
  font-size: 28px;
  color: #8c939d;
  width: 178px;
  height: 178px;
  line-height: 178px;
  text-align: center;
}
.co-image-uploader .image {
  width: 178px;
  height: 178px;
  display: block;
}
</style>
