<script setup>
import { onMounted, ref, watch } from "vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
onMounted(() => {
    const page = usePage();
    const flash = ref(page.props.flash);
    const delay = 2000;

    const notify = (status, message) => {
        switch (status) {
            case "success":
                toast.success(message, {
                    autoClose: delay,
                });
                break;
            case "warn":
                toast.warn(message, {
                    autoClose: delay,
                });
                break;
            case "error":
                toast.error(message, {
                    autoClose: delay,
                });
                break;
            default:
                toast(message, {
                    autoClose: delay,
                });
                break;
        }

    }
    notify(flash.value.status, flash.value.message);
});

</script>
<template></template>
