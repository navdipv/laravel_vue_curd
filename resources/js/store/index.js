import { createStore } from 'vuex';
import http from '@/utils/http';

export default createStore({
  state: {
    user: null,
    token: localStorage.getItem('token') || null,
  },
  mutations: {
    SET_USER(state, user) {
      state.user = user;
    },
    SET_TOKEN(state, token) {
      state.token = token;
      localStorage.setItem('token', token);
    },
    CLEAR_AUTH(state) {
      state.user = null;
      state.token = null;
      localStorage.removeItem('token');
    }
  },
  actions: {
    login({ commit }, credentials) {
      return http.post('/login', credentials)
        .then(({ data }) => {
          commit('SET_TOKEN', data.data.token);
          commit('SET_USER', data.data.user);
        })
        .catch(error => {
          console.error('Error on login', error);
        });
    },
    logout({ commit }) {
      commit('CLEAR_AUTH');
    }
  },
  getters: {
    isLoggedIn: state => !!state.token,
    currentUser: state => state.user,
  }
});
