<template>
  <div class="co-dialog-overlay" v-show="propVisible" @click="close('overlay')">
    <div class="co-dialog" @click.stop>
      <slot></slot>
      <a href="javascript:;" class="co-dialog-close glyphicon glyphicon-remove" @click="close('close')"></a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CoDialog',
  props: {
    visible: Boolean
  },
  methods: {
    close (type) {
      this.$emit('close', type)
      this.$emit('update:visible', false)
    }
  },
  computed: {
    propVisible () {
      return this.visible
    }
  }
}
</script>

<style lang="scss">
.co-dialog-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(#000, .5);
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  z-index: 2048;
}
.co-dialog {
  position: relative;
  box-sizing: border-box;
  height: 100%;
  width: 100%;
  padding: 2em;
  background: #fff;
  box-shadow: 4px 4px 8px rgba(#aaa, .5);
  @media (min-width: 500px) {
    width: 500px;
    height: 400px;
    border-radius: 1em;
  }
}
.co-dialog-close {
  position: absolute;
  top: 2em;
  right: 2em;
  width: 16px;
  height: 16px;
}
</style>
