// src/store/index.js
import { createStore } from 'vuex'

export default createStore({
  state() {
    return {
      token: localStorage.getItem('token') || null,
    };
  },
  mutations: {
    setToken(state, token) {
      state.token = token;
      localStorage.setItem('token', token);
    }
  },
  actions: {
    login({ commit }, credentials) {
      return axios.post('http://laravel_vue_app.localhost/api/login', credentials)
        .then(({ data }) => {
          commit('setToken', data.token);
        });
    },
    register({ commit }, credentials) {
      return axios.post('http://laravel_vue_app.localhost/api/register', credentials)
        .then(({ data }) => {
          commit('setToken', data.token);
        });
    }
  }
});
