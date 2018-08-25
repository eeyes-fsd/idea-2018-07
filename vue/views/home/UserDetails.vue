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
        :author="name"
        :datetime="item.created_at"
        :liked="item.like_count"
        :viewed="item.view_count">
      </IdeaCard>
    </div>
  </div>
</template>

<script>
import IdeaCard from './IdeaCard'

export default {
  name: 'UserDetails',
  props: {
    avatar: String,
    name: String,
    signature: String,
    articles: Array
  },
  components: {
    IdeaCard
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
  align-items: center;
}
.user__details-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
}
.user__details-avatar,
.user__details-details {
  margin-left: 30px;
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
@media screen and (max-width: 600px) {
  .user__details-articles {
    min-width: 450px;
    flex-grow: 1;
  }
}
@media screen and (max-width: 500px) {
  .user__details-articles {
    min-width: 300px;
    flex-grow: 1;
  }
}
@media screen and (max-width: 400px) {
  .user__details-articles {
    min-width: 150px;
    overflow: hidden;
    flex-grow: 1;
  }
}
</style>
