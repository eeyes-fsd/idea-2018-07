import Vue from 'vue'
import Vuex from 'vuex'
import createLogger from 'vuex/dist/logger'
import { debug } from '@/env'

Vue.use(Vuex)

export default new Vuex.Store({
  strict: debug,
  plugins: debug ? [createLogger()] : []
})
