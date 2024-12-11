<script setup>
import { onMounted } from 'vue';

onMounted(() => {
    const HTML = document.querySelector('html');
    HTML.class = "light-style layout-wide customizer-hide";
});
</script>
<template>
    <div class="authentication-wrapper authentication-cover bg-white">
        <div class="authentication-inner row">
            <slot></slot>
        </div>
    </div>
</template>
