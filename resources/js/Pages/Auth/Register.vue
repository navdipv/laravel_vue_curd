<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { useGeneral } from '../../Compasable/General';
const { asset } = useGeneral();
import http from '@/utils/http';

// Using ref to hold form data
const form = ref({
    name: "",
    email: "",
    password: "",
    remember: false
});

// Errors state
const errors = ref({});

// Setup router
const router = useRouter();
const route = useRoute();

const submit = async () => {
    try {
        // Send POST request to the server
        const response = await http.post('register', form.value);
        console.log('Registration successful:', response.data);

        router.push({ name: 'login' });

         // Redirect after successful registration
    } catch (e) {
        if (e.response && e.response.data && e.response.data.errors) {
            // Capture validation or other errors from the server
            errors.value = e.response.data.errors;
        } else {
            // Handle other types of errors (network issues, etc.)
            console.error('An error occurred:', e.response.data.message);
        }
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

            <h4 class="mb-3" style="margin-top: 130px;">
                <b>
                    <span style="color:#EC1F27">Hey,</span><br>
                    <span class="text-dark">Let’s Sign up!</span>
                </b>
            </h4>
            <form id="formAuthentication" class="mb-3" @submit.prevent="submit">
                <div class="mb-3">
                    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="john" autofocus="" tabindex="1"
                        v-model="form.name" maxlength="100">
                    <div class="text-danger" v-if="errors.name">{{ errors.name }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="john@example.com" autofocus="" tabindex="1"
                        v-model="form.email" maxlength="100">
                    <div class="text-danger" v-if="errors.email">{{ errors.email }}</div>
                </div>

                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input class="form-control form-control-merge" type="password" placeholder="············"
                            id="password" tabindex="2" v-model="form.password">
                        <span class="input-group-text cursor-pointer" @click="reveal('password')">
                            <i class="menu-icon tf-icons ti ti-eye"></i>
                        </span>
                    </div>
                    <div class="text-danger" v-if="errors.password">{{ errors.password }}</div>
                </div>
                <button class="btn btn-primary w-100" tabindex="3">Sign Up</button>
            </form>

            <p style="margin-top:100px;width: 100%;text-align: center;"><span class="text-dark">Already have an account?
                </span>
                <router-link :to="{ name: 'login' }">Login Now</router-link>
            </p>
        </div>
    </div>
</template>

<style scoped>
.loginForm .form-control {
    padding: 0.822rem 0.875rem !important;
}

.loginForm .btn-primary {
    padding: 0.822rem 0.875rem !important;
}
</style>
