import Vue from 'vue'
import Vuex from 'vuex'
import createLogger from 'vuex/dist/logger'
import requests from '@/api/requests'
import { debug } from '@/env'
import { sleep, getCookie, setCookie, delCookie } from '@/util'

Vue.use(Vuex)

const refreshTimeRate = 750 // 有效时间经过75%时刷新token

/**
 * 执行一次token刷新
 */
function refreshToken(commit) {
  return new Promise((resolve, reject) => {
    // let loginType = getLoginType()
    // let url = (loginType == 'organizations') ? '/organizations/refresh' : '/users/refresh'
    let url ='/users/refresh'
    requests.put(url)
    .then(data => {
      commit('setAccessToken', data.access_token)
      resolve(data.expires_in)
    })
    .catch(reject)
  })
}

export default new Vuex.Store({
  strict: debug,
  plugins: debug ? [createLogger()] : [],
  state: {
    accessToken: '', // 请求token
    refreshing: false, // 是否正在刷新token
    init: true, // 是否还在初始化
    isLogin: false,
    count: 0,
    userInfo: {} // 用户信息
  },
  getters: {
    getAccessToken: state => state.accessToken
  },
  mutations: {
    /**
     * 保存AccessToken
     */
    setAccessToken (state, token) {
      state.accessToken = token
      setCookie('access_token', token)
    },
    /**
     * 初始化结束
     */
    setInitOver (state) {
      state.init = false
    },
    /**
     * 保存用户信息
     */
    setUserInfo (state, info) {
      for (let key in info) {
        Vue.set(state.userInfo, key, info[key])
      }
    },
    /**
     * 设置是否正在刷新token
     */
    setRefreshing (state, value) {
      state.refreshing = value
    },
    /**
     * 设置已经登录
     */
    setLogin (state) {
      state.isLogin = true
    }
  },
  actions: {
    /**
     * 登录、保存accessToken、获取用户信息等初始化
     */
    async initialize ({ commit, dispatch }, payload) {
      if (payload) {
        // 有code，用code登录
        let data = await requests.post('/users/authorizations/callback', { code: payload.code })
        commit('setAccessToken', data.access_token)
        commit('setLogin')
        // 获取用户信息
        let info = await requests.get('/user')
        commit('setUserInfo', info)
        commit('setInitOver')
        // 开始自动刷新
        dispatch('autoRefreshToken', data.expires_in * refreshTimeRate)
      } else {
        let accessToken = getCookie('access_token')
        if (accessToken) {
          try {
            commit('setAccessToken', accessToken)
            let time = await refreshToken(commit) // 此函数也会setAccessToken
            commit('setLogin')
            // 获取用户信息
            let info = await requests.get('/user')
            commit('setUserInfo', info)
            commit('setInitOver')
            // 开始自动刷新
            dispatch('autoRefreshToken', time * refreshTimeRate)
          } catch (err) {
            console.log('AccessToken已过期')
            // 以非登录状态运行
            commit('setInitOver')
          }
        } else {
          // 否则以非登录状态运行
          commit('setInitOver')
        }
      }
    },
    /**
     * 自动刷新token
     */
    async autoRefreshToken ({ state, commit }, delay) {
      if (state.refreshing) {
        return
      }
      await sleep(delay)
      for (;;) {
        commit('setRefreshing', true)
        let time = await refreshToken(commit)
        commit('setRefreshing', false)
        await sleep(time * refreshTimeRate)
      }
    },
    logout () {
      delCookie('access_token')
      delCookie('laravel_session')
      location.href = "https://account.eeyes.net/logout"
    },
  }
})
