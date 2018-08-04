import axios from 'axios'
import { baseUrl } from '@/env'

var apiToken = '';

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
    config.headers['API-Token'] = apiToken
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
    if (data !== undefined && data.ret !== undefined) {
      if (data.ret == 200) {
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
        ret: 500,
        err_msg: '服务器无效'
      }
    }
    apiConfig.reporter(error)
    return Promise.reject(error)
  },
  function({ response: { data } }) {
    var error
    if (data !== undefined && data.ret !== undefined) {
      error = data
    } else {
      // 严重错误，状态码200
      // 但是没有ret说明后端框架加载失败
      error = {
        ret: 500,
        err_msg: '服务器无效'
      }
    }
    apiConfig.reporter(error)
    return Promise.reject(error)
  }
)

export default _axios

export function setApiToken(token) {
  apiToken = token
}
