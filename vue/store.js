import Vue from 'vue'
import Vuex from 'vuex'
import createLogger from 'vuex/dist/logger'
import { debug } from '@/env'
import { getCookie } from '@/util/index.js'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: debug,
  plugins: debug ? [createLogger()] : [],
  state:{
    ifLogin: false,
    count: 0
  },
  getters: {
    getAccessToken: () => getCookie('access_token')
  },
  mutations:{
    /**
     * 保存AccessToken
     */
    setAccessToken (state, token) {
      setCookie('access_token', token)
    },
    login (state){
      state.ifLogin = true
    }
  }
})

