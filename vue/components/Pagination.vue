<template>
  <el-pagination
    small
    layout="prev, pager, next"
    :pager-count="5"
    :page-size="pageSize"
    :total="total"
    :current-page="currentPage"
    @current-change="sendChangeEvent">
  </el-pagination>
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
