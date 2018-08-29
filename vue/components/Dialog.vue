<template>
  <div class="co-dialog-overlay" v-show="propVisible" @click="close('overlay')">
    <div class="co-dialog" @click.stop>
      <slot></slot>
      <div class="co-dialog-actions" :style="actionsStyles" v-if="showButtons">
        <button type="button" class="btn btn-default" v-if="showCancel" @click="close('cancel')">取消</button>
        <button type="button" class="btn btn-primary" v-if="showConfirm" @click="confirm">确认</button>
      </div>
      <div class="co-dialog-close">
        <a href="javascript:;" class="glyphicon glyphicon-remove" @click="close('close')"></a>
      </div>
    </div>
  </div>
</template>

<script>
const styleMap = {
  left: 'flex-start',
  center: 'center',
  right: 'flex-end'
}

export default {
  name: 'CoDialog',
  props: {
    visible: Boolean,
    buttons: {
      type: String,
      default: 'confirm,cancel'
    },
    position: {
      type: String,
      default: 'right'
    },
  },
  methods: {
    close (type) {
      this.$emit('close', type)
      this.$emit('update:visible', false)
    },
    confirm (event) {
      this.$emit('confirm', event)
    }
  },
  computed: {
    propVisible () {
      return this.visible
    },
    actionsStyles () {
      let justifyContent = styleMap[this.position] || 'flex-end';
      return {
        'justify-content': justifyContent
      }
    },
    buttonList () {
      return this.buttons.split(',')
    },
    showConfirm () {
      return this.buttonList.find(type => type == 'confirm') != undefined
    },
    showCancel () {
      return this.buttonList.find(type => type == 'cancel') != undefined
    },
    showButtons () {
      return this.showConfirm && this.showCancel
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
  overflow: scroll;
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
    height: auto;
    min-height: 2em;
    border-radius: 1em;
  }
}
.co-dialog-close {
  position: absolute;
  top: .8em;
  right: .8em;
  width: 16px;
  height: 16px;
}
.co-dialog .co-dialog-close a {
  text-decoration: none;
}
.co-dialog-actions {
  display: flex;
  flex-direction: row;
  align-items: center;
  button + button {
    margin-left: 12px;
  }
}
</style>
