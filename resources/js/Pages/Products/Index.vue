<script setup>
import { ref, onMounted } from "vue";
import axios from "axios"; // Ensure axios is installed and imported
import SortableColumn from "@/Components/Miscs/SortableColumn.vue";
import Pagination from "@/Components/Miscs/Pagination.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import http from "@/utils/http";
import { inject } from "vue";
const swal = inject('$swal');

import { useGeneral } from "../../Compasable/General";
const { asset,formatDate } = useGeneral();

// Reactive state for storing categories and possible errors
const products = ref([]);
const errors = ref({});

// Setting initial query parameters based on the URL
const params = new URLSearchParams(window.location.search);
const filterForm = ref({
    search: params.get("search") || "",
    orderBy: params.get("orderBy") || "id",
    sortAs: params.get("sortAs") || "asc",
    page: params.get("page") || 1,
    per_page: params.get("per_page") || 10,
});

// Function to fetch categories
const fetchProducts = async () => {
    try {
        const response = await http.get("/products", {
            params: filterForm.value,
        });
        products.value = response.data; // Assuming the response structure
    } catch (err) {
        console.error("Error fetching categories:", err);
        errors.value = err.response.data.errors || {
            message: "Failed to fetch data",
        };
    }
};

// Function to handle filter submissions
const filter = () => {
    fetchProducts();
};

const clear = () => {
    filterForm.value.search = "";
    fetchProducts();
}

const paginate = (page) => {
    filterForm.value.page = page;
    fetchProducts();
}

const confirmDelete = (id) => {
    swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0872BA",
        cancelButtonColor: "#ea5455",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            http.delete(`/products/${id}`)
                .then(() => {
                    fetchProducts();
                })
                .catch((err) => {
                    console.error("Error deleting category:", err);
                    errors.value = err.response.data.errors || {
                        message: "Failed to delete category",
                    };
                });
        }
    });
};

// Fetch data on component mount
onMounted(fetchProducts);
</script>

<template>
    <AuthenticatedLayout>
        <h4 class="mb-2">
            <span class="text-muted fw-light">Products / </span> Products
            Listing
        </h4>
        <div class="card mb-3">
            <div class="accordion show">
                <!-- Accordion and form for filtering -->
                <div id="accordionOne" class="accordion-collapse collapse show">
                    <div class="accordion-body border-top p-4">
                        <form @submit.prevent="filter">
                            <div class="row mb-3">
                                <div class="col-md-5">
                                    <label for="Name" class="form-label"
                                        >Search:</label
                                    >
                                    <input
                                        type="text"
                                        id="search"
                                        class="form-control"
                                        v-model="filterForm.search"
                                        placeholder="Search"
                                        maxlength="100"
                                    />
                                </div>
                                <div class="col-md-7 mt-4 text-right">
                                    <button
                                        type="submit"
                                        class="btn btn-primary me-1">
                                        Search
                                    </button>

                                    <button
                                        @click="clear"
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        >
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header border-bottom p-2">
                <div class="d-flex justify-content-between align-content-center p-1">
                    <div class="row">
                        <div class="col-md-12">
                            <select class="form-select" v-model="filterForm.per_page" @change="filter">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                            <router-link
                                :to="{ name: 'products-create' }"
                                class="btn btn-primary">
                                <i class="menu-icon text-white tf-icons ti ti-plus"></i>  Add Product
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table for displaying categories -->
            <div class="card-body table-responsive text-nowrap">
                <table class="table table-striped" v-if="products.data">
                    <thead>
                        <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Categories</th>
                                <th>Status</th>
                                <th>Created At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(row, index) in products.data.data"
                            :key="index"
                        >
                            <td>
                                    <div v-if="row.image_url" class="image-preview">
                                        <img :src="row.image_url" alt="Preview" class="img-thumbnail mt-2"  accept="image/png, image/jpeg, image/gif" style="max-height:50px;border-radius:10px">
                                    </div>
                            </td>
                            <td>{{ row.name }}</td>
                            <td>$ {{ row.price }}</td>
                            <td>
                                <span v-for="category in row.categories">
                                <span class="badge rounded-pill bg-label-info ms-2">{{ category.category?.name || 'N/A' }}</span>
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-label-success" v-if="row.status == 1">Active</span>
                                <span class="badge rounded-pill bg-label-warning" v-else>Inactive</span>
                            </td>
                            <td>{{ formatDate(row.created_at) }}</td>
                            <td>

                                <router-link :to="{ name: 'products-edit', params: { id: row.ulid } }" class="text-info">
                                    <i class="menu-icon text-info tf-icons ti ti-edit"></i>
                                </router-link>

                                <span
                                    @click="confirmDelete(row.ulid)"
                                    class="cursor-pointer"
                                >
                                    <i
                                        class="menu-icon text-danger tf-icons ti ti-trash"
                                    ></i>
                                </span>
                            </td>
                        </tr>
                        <tr v-if="products.data.total === 0">
                            <td colspan="7" class="text-center">
                                No products available
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <Pagination v-if="products.data" :data="products" :pageMethod="paginate"></Pagination>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
