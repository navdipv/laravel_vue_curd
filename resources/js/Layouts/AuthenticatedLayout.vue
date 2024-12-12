<script setup>
import Navbar from '@/Components/AuthLayout/Navbar.vue';
import SideBar from '@/Components/AuthLayout/SideBar.vue';
import Footer from "@/Components/AuthLayout/Footer.vue";
import { onMounted, reactive } from 'vue';

onMounted(() => {
    const html = document.querySelector('html');
    html.className = "light-style layout-navbar-fixed layout-menu-fixed layout-compact";
});

const flags = reactive({
    allowFluid: false,
    expanded: true,
});

const forceCollapse = () => {
    const html = document.querySelector('html');
    html.className = "light-style layout-navbar-fixed layout-menu-fixed layout-compact layout-menu-collapsed";
    flags.expanded = false;
}
const forceExpand = () => {
    const html = document.querySelector('html');
    html.className = "light-style layout-navbar-fixed layout-menu-fixed layout-compact";
    flags.expanded = true;
}
const collapse = () => {
    const html = document.querySelector('html');
    if (flags.expanded && flags.allowFluid) {
        html.className = "light-style layout-navbar-fixed layout-menu-fixed layout-compact layout-menu-collapsed";
        flags.expanded = false;
    }
};
const expand = () => {
    const html = document.querySelector('html');
    if (!flags.expanded && flags.allowFluid) {
        html.className = "light-style layout-navbar-fixed layout-compact layout-menu-fixed layout-menu-expanded";
        flags.expanded = true;
    }
};
const fluidSidebar = () => {
    flags.allowFluid = !flags.allowFluid;
};
</script>

<template>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <SideBar :flags="flags" :collapse="collapse" :expand="expand" :fluidSidebar="fluidSidebar"
                :forceCollapse="forceCollapse" :forceExpand="forceExpand" />
            <div class="layout-page">
                <Navbar :flags="flags" :collapse="collapse" :expand="expand" :fluidSidebar="fluidSidebar"
                    :forceCollapse="forceCollapse" :forceExpand="forceExpand" />
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <slot></slot>
                    </div>
                    <Footer />
                </div>
            </div>
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
<style>
.multiselect-option.is-selected {
    background: var(--ms-option-bg-selected, #7367f0);
    color: var(--ms-option-color-selected, #fff);
}

.multiselect-option.is-selected.is-pointed {
    background: var(--ms-option-bg-selected, #7367f0);
    color: var(--ms-option-color-selected, #fff);
}

.multiselect-option.is-selected:hover {
    background: var(--ms-option-bg-selected, #7367f0);
    color: var(--ms-option-color-selected, #fff);
}

.multiselect.is-active {
    border: 1px solid #7367f0;
    box-shadow: 0 3px 10px 0 rgba(34, 41, 47, 0.1);
    filter: none;
}
</style>
