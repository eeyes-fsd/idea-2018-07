import axios from 'axios'
import { debug, baseUrl } from '@/env'

var accessToken = 'null'

function debugReporter({ debug }) {
  let log = console.log // eslint-disable-line
  const keys = ['class', 'file', 'line']
  keys.forEach(key => {
    log(key + ': ' + debug[key])
  })
  if (debug.trace instanceof Array) {
    log('trace:')
    debug.trace.forEach(line => log(line))
  }
}

var reporter = debug ? debugReporter : err => err

// Full config:  https://github.com/axios/axios#request-config
// axios.defaults.baseURL = process.env.baseURL || process.env.apiUrl || ''
// axios.defaults.headers.common['Authorization'] = AUTH_TOKEN
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'

let config = {
  baseURL: baseUrl
  // timeout: 60 * 1000, // Timeout
  // withCredentials: true, // Check cross-site Access-Control
}

const _axios = axios.create(config)

_axios.interceptors.request.use(
  function(config) {
    config.headers['Authorization'] = 'Bearer ' + accessToken
    return config
  },
  function(error) {
    // Do something with request error
    return Promise.reject(error)
  }
)

// Add a response interceptor
_axios.interceptors.response.use(
  function({ data }) {
    var error
    if (data !== undefined && data.status_code !== undefined) {
      if (data.status_code >= 200 && data.status_code < 400) {
        // 正确响应
        return data.data
      } else {
        // 一般错误
        error = data
      }
    } else {
      // 严重错误，状态码200
      // 但是没有ret说明后端框架加载失败
      error = {
        status_code: 500,
        message: '服务器无效'
      }
    }
    reporter(error)
    return Promise.reject(error)
  },
  function({ response: { data } }) {
    var error
    if (data !== undefined && data.status_code !== undefined) {
      error = data
    } else {
      // 严重错误，状态码200
      // 但是没有ret说明后端框架加载失败
      error = {
        status_code: 500,
        message: '服务器无效'
      }
    }
    reporter(error)
    return Promise.reject(error)
  }
)

export default _axios

export function setAccessToken(token) {
  accessToken = token
}

export function setReporter(r) {
  reporter = r
}
