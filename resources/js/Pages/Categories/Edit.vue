<script setup>
import { reactive, ref } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useRouter, useRoute } from 'vue-router';
import { useGeneral } from '../../Compasable/General';
import Multiselect from "@vueform/multiselect";
const { asset } = useGeneral();
import http from '@/utils/http';
// Setup router
const router = useRouter();
const route = useRoute();

const parent_categories = ref([]);
const category = ref([]);

const getCategories = async () => {
    try {
        const response = await http.get('categories/parent', {
            params: { ulid : route.params.id },
        });
        parent_categories.value = response.data.data;
    } catch (e) {
        console.log(e);
    }
};

const getCategory = async () => {
    try {
        const response = await http.get('categories/'+route.params.id);
        category.value = response.data.data;
        form.value.id = category.value.id;
        form.value.name = category.value.name;
        form.value.parent_id = category.value.parent_id;
    } catch (e) {
        console.log(e);
    }
};

getCategories();
getCategory();

const form = ref({
  id: '',
  name: '',
  parent_id: '',
});

// Errors state
const errors = ref({});



const submit = async () => {
    try {
        // Send POST request to the server
        const response = await http.put('categories/'+route.params.id, form.value);
        console.log('Registration successful:', response.data);

        router.push({ name: 'categories-index' });

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
</script>

<template>
  <AuthenticatedLayout>
    <h4 class="mb-2">
      <span class="text-muted fw-light">Categories / </span>Edit Category
    </h4>
    <div class="row">
      <div class="col-md-8">
        <form @submit.prevent="submit">
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Details</h5>
            </div>
            <div class="card-body">
              <div class="row mb-2">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" for="Name">
                      Name <span class="text-danger">*</span> : </label>
                    <input type="text" class="form-control" placeholder="Name" v-model="form.name" autofocus />
                    <div class="text-danger" v-if="errors.name">
                      {{ errors.name }}
                    </div>
                  </div>
                </div>
                <div class="col-md-6" v-if="category.subcategories?.length == 0">
                    <div class="form-group">
                        <label class="form-label" for="Name">
                            Parent Category
                            : </label>
                        <Multiselect v-model="form.parent_id" :searchable="true"
                            :options="parent_categories" mode="single" :close-on-select="true"
                            placeholder="Select a category" />
                        <div class="text-danger" v-if="errors.parent_id">
                            {{ errors.parent_id }}
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary waves-effect me-2">
                Submit
              </button>

              <router-link
                    :to="{ name: 'categories-index' }"
                    class="btn btn-outline-secondary waves-effect">
                    Discard
                </router-link>
            </div>
          </div>
        </form>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
