<template>
  <ul class="nav navbar-nav">
    <li v-for="(root, i) in roots" :key="i"
      :class="{ 'active': categoryId == root.id, 'dropdown': root.children.length != 0, 'open': activeId == root.id}"
      @mouseover="open(root.id)"
      @mouseout="open(0)">
      <router-link :to="`/category/${root.id}`">
        <span>{{ root.name }}</span>
        <span v-if="categoryId == root.id" class="sr-only">(current)</span>
        <span class="caret" v-if="root.children.length != 0"></span>
      </router-link>
      <ul class="dropdown-menu"
        v-if="root.children.length != 0"
        @mouseover="open(root.id)"
        @mouseout="open(0)">
        <li v-for="(link, j) in root.children" :key="j">
          <router-link :to="`/category/${link.id}`">
            <span>{{ link.name }}</span>
            <span v-if="categoryId == link.id" class="sr-only">(current)</span>
          </router-link>
        </li>
      </ul>
    </li>
  </ul>
</template>

<script>
import requests from '@/api/requests.js'

export default {
  name: 'NavMenu',
  props: {
    // categories: Array
  },
  data () {
    return {
      activeId: 0,
      categories: [],
      roots: []
    }
  },
  mounted () {
    this.getCategories()
  },
  methods: {
    async getCategories() {
      this.categories = await requests.get('/categories')
      this.parseCategories()
    },
    open (id) {
      this.activeId = id
    },
    parseCategories () {
      let data = this.categories
      let roots = data.filter(item => item.parent_id == 0)
      this.roots = roots.map(root => {
        return {
          ...root,
          children: data.filter(item => item.parent_id == root.id)
        }
      })
    },
  },
  computed: {
    categoryId () {
      return this.$route.params.id
    }
  }
}
</script>
