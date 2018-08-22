import requests, { getLoginType } from '@/api/requests'
import { setCookie } from '@/util';

var running = false

function sleep(time) {
  return new Promise(resolve => {
    setTimeout(resolve, time)
  })
}

function refresh() {
  return new Promise((resolve, reject) => {
    let loginType = getLoginType()
    let url = (loginType == 'organizations') ? '/organizations/refresh' : '/users/refresh'
    requests.put(url)
    .then(data => {
      setCookie('access_token', data.access_token)
      resolve(data.expires_in)
    })
    .catch(reject)
  })
}

export default async function autoRefreshToken() {
  if (running) {
    await refresh()
    return
  }
  for (;;) {
    running = true
    let time = await refresh()
    await sleep(time * 500)
  }
}
