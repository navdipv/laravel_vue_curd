<script setup>
import { useStore } from "vuex";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { computed } from "vue";
const store = useStore();
const user = computed(() => store.state.user);
const isLoading = computed(() => store.state.isLoading);
</script>


<template>
    <AuthenticatedLayout v-if="user">
        <div>
            <h1>Welcome, {{ user.name }}</h1>
            <p>Email: {{ user.email }}</p>
        </div>
    </AuthenticatedLayout>
</template>
