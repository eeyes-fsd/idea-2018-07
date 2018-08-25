<template>
  <div class="co-pagination">
    <el-pagination
      layout="prev, pager, next"
      :pager-count="5"
      :page-size="pageSize"
      :total="total"
      :current-page="currentPage"
      @current-change="sendChangeEvent">
    </el-pagination>
  </div>
</template>

<script>
export default {
  name: 'CoPagination',
  props: {
    pagination: Object
  },
  methods: {
    sendChangeEvent (payload) {
      this.$emit('change', payload)
    }
  },
  computed: {
    pageSize () {
      return this.pagination.per_page || 0
    },
    total () {
      return this.pagination.total || 0
    },
    currentPage () {
      return this.pagination.current_page || 1
    }
  }
}
</script>

<style>
.co-pagination {
  text-align: center;
}
.co-pagination > .el-pagination {
  display: inline-block;
}
</style>
