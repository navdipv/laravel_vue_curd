<script setup>
import { useStore } from "vuex";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { computed } from "vue";
const store = useStore();
const user = computed(() => store.state.user);
import BarChart from "../components/Miscs/BarChart.vue";
</script>

<template>
    <AuthenticatedLayout v-if="user">
        <div class="row">

            <div class="col-md-12 mb-3">
                <h1>Welcome, {{ user.name }}</h1>
                <p>Email: {{ user.email }}</p>
            </div>

            <div class="col-md-6 mb-3">
                <BarChart />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
