<script setup>
import { ref, onMounted } from "vue";
import axios from "axios"; // Ensure axios is installed and imported
import SortableColumn from "@/Components/Miscs/SortableColumn.vue";
import Pagination from "@/Components/Miscs/Pagination.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import http from "@/utils/http";
import { inject } from "vue";
const swal = inject('$swal');


// Reactive state for storing categories and possible errors
const categories = ref([]);
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
const fetchCategories = async () => {
    try {
        const response = await http.get("/categories", {
            params: filterForm.value,
        });
        categories.value = response.data; // Assuming the response structure
    } catch (err) {
        console.error("Error fetching categories:", err);
        errors.value = err.response.data.errors || {
            message: "Failed to fetch data",
        };
    }
};

// Function to handle filter submissions
const filter = () => {
    fetchCategories();
};

const clear = () => {
    filterForm.value.search = "";
    fetchCategories();
}

const paginate = (page) => {
    filterForm.value.page = page;
    fetchCategories();
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
            http.delete(`/categories/${id}`)
                .then(() => {
                    fetchCategories();
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
onMounted(fetchCategories);
</script>

<template>
    <AuthenticatedLayout>
        <h4 class="mb-2">
            <span class="text-muted fw-light">Categories / </span> Categories
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
                                :to="{ name: 'categories-create' }"
                                class="btn btn-primary">
                                <i class="menu-icon text-white tf-icons ti ti-plus"></i>  Add Category
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table for displaying categories -->
            <div class="card-body table-responsive text-nowrap">
                <table class="table table-striped" v-if="categories.data">
                    <thead>
                        <tr>
                            <th scope="col">
                                <SortableColumn
                                    label="Name"
                                    :form="filterForm"
                                    formSubmit="filter"
                                    orderBy="name"
                                />
                            </th>
                            <th scope="col">Parent Category</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(category, index) in categories.data.data"
                            :key="index"
                        >
                            <td>{{ category.name }}</td>
                            <td>
                                {{
                                    category.parent
                                        ? category.parent.name
                                        : "N/A"
                                }}
                            </td>
                            <td>

                                <router-link :to="{ name: 'categories-edit', params: { id: category.ulid } }" class="text-info">
                                <i class="menu-icon text-info tf-icons ti ti-edit"></i>
                                </router-link>

                                <span
                                    @click="confirmDelete(category.ulid)"
                                    class="cursor-pointer"
                                >
                                    <i
                                        class="menu-icon text-danger tf-icons ti ti-trash"
                                    ></i>
                                </span>
                            </td>
                        </tr>
                        <tr v-if="categories.data.total === 0">
                            <td colspan="5" class="text-center">
                                No categories available
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <Pagination v-if="categories.data" :data="categories" :pageMethod="paginate"></Pagination>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
