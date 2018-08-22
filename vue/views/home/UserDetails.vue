<template>
  <div class="user__details user__details-flex">
    <div class="user__details-info user__details-flex">
      <div class="user__details-avatar">
        <img class="user__details-img" :src="avatar" :alt="name">
      </div>
      <div class="user__details-details">
        <h5>{{ name }}</h5>
        <p>{{ signature }}</p>
      </div>
    </div>
    <div class="user__details-articles user__details-flex">
      <IdeaCard
        v-for="(item,key) in articles"
        :key="key"
        class="article"
        img-src=""
        :title="item.title"
        :author="item.author.nickname"
        :datetime="item.created_at"
        :liked="item.like_count"
        :viewed="item.view_count">
      </IdeaCard>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserDetails',
  props: {
    avatar: String,
    name: String,
    signature: String
  },
  computed: {
    articles: () => []
  }
}
</script>

<style lang="scss">
.user__details-flex {
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
}
.user__details {
  padding: 20px 0;
}
.user__details-info {
  flex-grow: 1;
}
.user__details-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
}
.user__details-img {
  width: 100%;
  height: 100%;
  border-radius: 50%;
}
.user__details-articles {
  .article {
    margin: 10px;
  }
}
</style>
