<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useGeneral } from '@/Compasable/General';
import { inject } from "vue";
const swal = inject('$swal');
const { asset } = useGeneral();


onMounted(() => {
    SideLinks.currentActive = window.location.pathname.split('/');
});

const logout = () => {
    swal.fire({
        title: 'Are you sure?',
        text: "Are you sure you want to logout session?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EC1F27',
        cancelButtonColor: '#0872BA',
        confirmButtonText: 'Yes, logout!'
    }).then((result) => {
        if (result.isConfirmed) {

        }
    });
}

</script>
<template>
    <!-- Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" @mouseenter="fluidExpand"
        @mouseleave="fluidCollapse">
        <div class="app-brand demo">
            <Link href="" class="app-brand-link" style="height: 100px;">
            <img :src="asset('images/Tap2TipTM1.svg')" alt="" srcset=""
                style="height: 80px;margin: 10px;margin-top:20px;margin-bottom:20px">
            </Link>
            <a class="layout-menu-toggle text-large ms-auto d-block d-md-none">
                <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle text-primary" id="allowIcon"
                    @click="allowSideChange('allowIcon')"></i>
                <i class="ti ti-x d-block d-xl-none ti-sm align-middle" @click="forceCollapse()"></i>
            </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1" style="overflow-y: auto;overflow-x: hidden;">

            <!-- Dashboards -->
            <li class="menu-item" :class="SideLinks.currentActive[1] === 'dashboard' ? 'active' : ''"
                v-if="user.role_id != 6">
                <Link href="" class="menu-link">
                <i class="menu-icon tf-icons ti ti-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
                </Link>
            </li>

        </ul>

    </aside>
    <!-- / Menu -->
</template>


<style>
li.active>a>.qrcode>path {
    fill: white;
}
</style>
