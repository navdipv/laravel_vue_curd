<script setup>
import { useStore } from "vuex";
import { computed } from "vue";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';

const store = useStore();
const isLoading = computed(() => store.state.isLoading);
</script>

<template>
    <div>
        <Loading :active="isLoading"
                 :can-cancel="false"
                 :is-full-page="true"
                 color="#EC1F27"
                 opacity="0.7"
                 loader="bars"
                 transition="fade">
        </Loading>
        <router-view />
    </div>
</template>
