<template>
  <div
    class="alert co-message-box"
    role="alert"
    :class="[
      `alert-${type}`,
      visible ? '' : 'co-message-box-hidden'
    ]">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="close">
      <span aria-hidden="true">&times;</span>
    </button>
    <strong>{{ showedTitle }}</strong>
    <span>{{ message }}</span>
  </div>
</template>

<script>
export default {
  name: 'CoMessageBox',
  props: {
    visible: Boolean,
    message: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'success'
    },
    title: {
      type: String,
      required: false
    },
    autoHide: {
      type: Boolean,
      default: true
    },
    hideTime: {
      type: Number,
      default: 5000
    }
  },
  mounted () {
    if (this.autoHide) {
      setTimeout(this.close, this.hideTime);
    }
  },
  methods: {
    close () {
      this.$emit('update:visible', false)
    }
  },
  watch: {
    visible (newVisible) {
      if (newVisible && this.autoHide) {
        setTimeout(this.close, this.hideTime);
      }
    }
  },
  computed: {
    showedTitle () {
      return this.title || this.type[0].toUpperCase() + this.type.slice(1)
    }
  }
}
</script>

<style>
.co-message-box {
  position: fixed;
  top: 0;
  left: 50%;
  margin: .8em 0 0;
  display: inline-block;
  transform: translateX(-50%);
  text-align: center;
  opacity: 1;
  transition: transform .5s, opacity .5s;
  z-index: 4096;
}
.co-message-box-hidden {
  opacity: 0;
  transform: translateY(-100%);
}
</style>
