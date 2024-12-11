<script setup>
import { ref, watch } from 'vue';
import { useGeneral } from '@/Compasable/General';

const { asset } = useGeneral();

const HTML = document.querySelector('html');

const logout = () => {
    alert("call");
}

const sidebar = () => {
    if (sidebarFlag === false) {
        sidebarFlag = true;
        sideExpand();
    } else {
        sidebarFlag = false;
        sideCollapse();
    }

}

const sideCollapse = () => {
    HTML.className = "light-style layout-navbar-fixed layout-compact layout-menu-fixed";
}
const sideExpand = () => {
    HTML.className = "light-style layout-navbar-fixed layout-compact layout-menu-fixed layout-menu-expanded";
}

</script>

<template>
    <!-- Navbar -->

    <nav class="layout-navbar navbar navbar-expand-xl  align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none" @click="sidebar()">
            <a class="nav-item nav-link  me-xl-4" href="javascript:void(0)" style="margin-left:10px;">
                <i class="ti ti-menu-2 ti-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow d-flex" style="align-items: center;gap: 10px;"
                        href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar">
                            <img :src="asset('images/avtar.png')" alt class="rounded-circle"
                                style="aspect-ratio:1/1;" />
                        </div>
                        <span class="fw-medium d-block">
                            <b>User Name</b>
                            <br>
                        </span>

                    </a>
                    <ul class="dropdown-menu dropdown-menu-end d-none">
                        <li>
                            <a class="dropdown-item">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img :src="asset('images/avtar.png')" alt class="rounded-circle"
                                                style="aspect-ratio:1/1;" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-medium d-block">User Name</span>
                                        <small class="text-muted">Admin</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>

                        <li>
                            <Link class="dropdown-item" href="">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">My Profile</span>
                            </Link>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <button class="dropdown-item" @click="logout">
                                <i class="ti ti-logout me-2 ti-sm"></i>
                                <span class="align-middle">Log Out</span>
                            </button>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>

    </nav>

    <!-- / Navbar -->
</template>
