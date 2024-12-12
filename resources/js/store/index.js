import { createStore } from "vuex";
import http from "@/utils/http";

export default createStore({
    state: {
        user: null,
        isLoading: false,
        token: localStorage.getItem("token") || null,
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user;
        },
        SET_TOKEN(state, token) {
            state.token = token;
            localStorage.setItem("token", token);
        },
        CLEAR_AUTH(state) {
            state.user = null;
            state.token = null;
            localStorage.removeItem("token");
        },
        SET_LOADING(state, status) {
            state.isLoading = status;
        },
    },
    actions: {
        login({ commit }, credentials) {
            return http
                .post("/login", credentials)
                .then(({ data }) => {
                    commit("SET_TOKEN", data.data.token);
                    commit("SET_USER", data.data.user);
                })
                .catch((error) => {
                    console.error("Error on login", error);
                });
        },
        logout({ commit }) {
            return http
                .get("logout")
                .then(({ data }) => {
                    commit("CLEAR_AUTH");
                })
                .catch((error) => {
                    console.error("Error on logout", error);
                });
        },
        getProfile({ commit }) {
            return http
                .get("/profile") // Assuming '/profile' endpoint returns user data
                .then(({ data }) => {
                    commit("SET_USER", data.data.user);
                })
                .catch((error) => {
                    console.error("Error fetching profile:", error);
                    commit("CLEAR_AUTH"); // Optional: Clear auth if error (e.g., token invalid)
                });
        },
        showLoader({ commit }) {
            commit("SET_LOADING", true);
        },
        hideLoader({ commit }) {
            commit("SET_LOADING", false);
        },
    },
    getters: {
        isLoggedIn: (state) => !!state.token,
        currentUser: (state) => state.user,
    },
});
