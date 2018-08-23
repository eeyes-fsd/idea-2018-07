<template>
  <div class="idea-card">
    <router-link :to="`/article/${id}`">
      <div class="idea-card-cover">
        <img class="idea-card-cover" :src="imgSrc" :alt="''">
      </div>
    </router-link>
    <div class="idea-card-details">
      <h6 class="idea-card-title one-line one-line-title">{{ title }}</h6>
      <p class="idea-card-author one-line">{{ author }}</p>
      <p class="idea-card-date one-line">{{ date }}</p>
      <hr class="idea-card-hr">
      <div class="idea-card-actions">
        <span class="glyphicon glyphicon-thumbs-up"></span>
        <span class="number liked">{{ liked }}</span>
        <span class="glyphicon glyphicon-eye-open"></span>
        <span class="number liked">{{ viewed }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'IdeaCard',
  props: {
    imgSrc: String,
    title: String,
    author: String,
    datetime: String,
    liked: Number,
    viewed: Number,
    id: Number,
  },
  computed: {
    date () {
      return this.datetime.replace(/\s*\d+(:\d+)+/g, '')
    }
  }
}
</script>

<style lang="scss">
.idea-card {
  width: 160px;
  height: 230px;
  border-radius: 12px;
  box-shadow: 1px 1px 10px rgba(#ccc, 0.35);
  .one-line {
    width: 100%;
    height: 1.6em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .one-line-title {
    height: 1.1em;
  }
}
.idea-card-cover {
  width: 160px;
  height: 130px;
  border: none;
}
.idea-card-details {
  margin: 0 11px;
}
.idea-card-title {
  margin: 11px 0;
  font-size: 12px;
  font-weight: bold;
}
.idea-card-author {
  margin: 0;
  font-size: 10.4px;
}
.idea-card-date {
  margin: 0;
  font-size: 9.6px;
  color: #ccc;
}
.idea-card-hr {
  margin: 4px 0;
}
.idea-card-actions {
  font-family: Arial, Helvetica, sans-serif;
  .glyphicon {
    color: #ccc;
  }
  .glyphicon,
  .number {
    font-size: 12px;
  }
}
</style>
