<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Toast from '@/Components/Miscs/Toast.vue';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { useGeneral } from '../../Compasable/General';
const { asset } = useGeneral();
import { useStore } from 'vuex';
import axios from 'axios';
// Using ref to hold form data
const form = ref({
    user_name: "",
    password: "",
    remember: false
});

// Errors state
const errors = ref({});

const route = useRoute();

const submit = async () => {
    try {
        
        store.dispatch('login', {
            email: email.value,
            password: password.value
        });

    } catch (e) {
        errors.value = e.response.data.errors;
    }
};

const reveal = (field) => {
    const input = document.getElementById(field);
    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
};

// Define the layout using defineExpose to be recognized by <script setup>
defineExpose({
    layout: GuestLayout,
});
</script>

<template>
    <Head title="Login" />
    <div class="d-flex col-12 col-lg-6 align-items-center p-sm-5 p-4 loginForm m-auto">
        <div class="w-px-500 mx-auto">
            
            <h4 class="mb-1" style="margin-top: 130px;">
                <b>
                    <span style="color:#EC1F27">Hey,</span><br>
                    <span class="text-dark">Let’s Sign in!</span>
                </b>
            </h4>
            <p class="mb-4">Welcome back. You’ve been missed!</p>
            <form id="formAuthentication" class="mb-3" @submit.prevent="submit">
                <div class="mb-3">
                    <label class="form-label" for="user_name">User Name <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="john@example.com" autofocus="" tabindex="1" v-model="form.user_name" maxlength="100">
                    <div class="text-danger" v-if="errors.user_name">{{ errors.user_name }}</div>
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input class="form-control form-control-merge" type="password" placeholder="············" id="password" tabindex="2" v-model="form.password">
                        <span class="input-group-text cursor-pointer" @click="reveal('password')"><font-awesome-icon :icon="['fad', 'eye']" /></span>
                    </div>
                    <div class="text-danger" v-if="errors.password">{{ errors.password }}</div>
                </div>
                <button class="btn btn-primary w-100" tabindex="3">Log In</button>
            </form>

            <p style="margin-top:100px;width: 100%;text-align: center;"><span class="text-dark">Don’t have an account? </span> 
                <router-link :to="{ name: 'register' }">Register Now</router-link>
            </p>

        </div>
    </div>
    <Toast v-if="route.query.status" />
</template>

<style scoped>
.loginForm .form-control {
    padding: 0.822rem 0.875rem!important;
}
.loginForm .btn-primary {
    padding: 0.822rem 0.875rem!important;
}
</style>
