<script setup>
import { useGeneral } from "@/Compasable/General";
const { asset } = useGeneral();
</script>
<template>
    <!-- Menu -->
    <aside
        id="layout-menu"
        class="layout-menu menu-vertical menu bg-menu-theme"
    >
        <div class="app-brand demo">
            <a href="#" class="app-brand-link" style="height: 100px">
                <img
                    :src="asset('images/Logo_1.png')"
                    alt=""
                    srcset=""
                    style="
                        height: 80px;
                        margin: 10px;
                        margin-top: 20px;
                        margin-bottom: 20px;
                    "
                />
            </a>
            <a class="layout-menu-toggle text-large ms-auto d-block d-md-none">
                <i
                    class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle text-primary"
                    id="allowIcon"
                    @click="allowSideChange('allowIcon')"
                ></i>
                <i
                    class="ti ti-x d-block d-xl-none ti-sm align-middle"
                    @click="forceCollapse()"
                ></i>
            </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul
            class="menu-inner py-1"
            style="overflow-y: auto; overflow-x: hidden"
        >
            <!-- Dashboards -->
            <li class="menu-item" :class="{ 'active': $route.name === 'home' }">
                <router-link :to="{ name: 'home' }" class="menu-link" >
                    <i class="menu-icon tf-icons ti ti-home"></i>
                    <div data-i18n="Dashboard">Dashboard</div>
                </router-link>
            </li>

            <li class="menu-item" :class="{ 'active': ($route.name === 'categories-index' || $route.name === 'categories-create' || $route.name === 'categories-edit') }">
                <router-link
                    :to="{ name: 'categories-index' }"
                    class="menu-link"
                >
                    <i class="menu-icon tf-icons ti ti-category"></i>
                    <div data-i18n="Category">Category</div>
                </router-link>
            </li>

            <li class="menu-item" :class="{ 'active': ($route.name === 'products-index' || $route.name === 'products-create' || $route.name === 'products-edit') }">
                <router-link :to="{ name: 'products-index' }" class="menu-link">
                    <i class="menu-icon tf-icons ti ti-package"></i>
                    <div data-i18n="Product">Product</div>
                </router-link>
            </li>
        </ul>
    </aside>
    <!-- / Menu -->
</template>

<style>
li.active > a > .qrcode > path {
    fill: white;
}
</style>
