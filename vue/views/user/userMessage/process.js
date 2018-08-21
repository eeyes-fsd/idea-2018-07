export const article_liked = {
  avatar: 'like_author_avatar',
  name: 'like_author_name',
  title: 'like_author_name',
  body () {
    return '赞了你的文章'
  },
  time: 'created_at'
}

export const article_replied = {
  avatar: 'reply_author_avatar',
  name: 'reply_author_name',
  title: 'reply_author_name',
  body (noti) {
    return `${noti.reply_author_name}回复了你的文章：${noti.body}`
  },
  time: 'created_at'
}

export const private_massage = { // <><> 这里有后端的拼写错误
  avatar: 'author_avatar',
  name: 'author_name',
  title (noti) {
    return `${noti.author_name}偷偷跟你说：`
  },
  body: 'body',
  time: 'created_at'
}

export const reply_liked = {
  avatar: 'like_author_avatar',
  name: 'like_author_name',
  title: 'like_author_name',
  body () {
    return '赞了你的回复'
  },
  time: 'created_at'
}

export const reply_replied = {
  avatar: 'reply_author_avatar',
  name: 'reply_author_name',
  title: 'reply_author_name',
  body (noti) {
    return `${noti.parent_reply_body}<br>${noti.reply_author_name}回复了你的回复：${noti.body}`
  },
  time: 'created_at'
}
