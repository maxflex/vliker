import Vue from 'vue';
import Vuex from 'vuex';
import auth from './auth'
import task from './task'
import message from './message'

Vue.use(Vuex);

const store = new Vuex.Store({
  modules: {
    auth,
    task,
    message,
  },
});

export default store;
