<script setup>
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useRouter } from "vue-router";
import http from "@/utils/http";
import Multiselect from "@vueform/multiselect";

const imageFile = ref(null);  // For storing the file object
const imagePreviewUrl = ref(''); // To store the URL of the image preview

const onFileChange = (event) => {
  const files = event.target.files;
  if (files.length > 0) {
    const file = files[0];
    // Check if the file is an image
    if (file.type.startsWith('image/')) {
      imageFile.value = file;
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreviewUrl.value = e.target.result;
      };
      reader.onerror = (error) => {
        console.error('Error reading file:', error);
        imagePreviewUrl.value = '';  // Reset on error
      };
      reader.readAsDataURL(file);
    } else {
      alert("Only image files are allowed.");
      imageFile.value = null;
      imagePreviewUrl.value = '';
    }
  } else {
    imageFile.value = null;
    imagePreviewUrl.value = '';
  }
};

const form = ref({
  name: "",
  description: "",
  price: null,
  image: null,
  categories: [],
  status: 1, // Defaulting to active status
});

const errors = ref({});
const categories = ref([]);
const router = useRouter();

// Modify the submit method to include FormData
const submit = async () => {
  const formData = new FormData();
  formData.append('name', form.value.name);
  formData.append('price', form.value.price);
  formData.append('categories', form.value.categories);
  formData.append('status', form.value.status);
  formData.append('description', form.value.description);
  if (imageFile.value) {
    formData.append('image', imageFile.value);
  }

  try {
    const response = await http.post('products', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    console.log("Product creation successful:", response.data);
    router.push({ name: "products-index" });
  } catch (e) {
    if (e.response && e.response.data && e.response.data.errors) {
      errors.value = e.response.data.errors;
    } else {
      console.error("An error occurred:", e.message);
    }
  }
};

const getCategories = async () => {
    try {
        const response = await http.get('categories/all');
        categories.value = response.data.data;
    } catch (e) {
        console.log(e);
    }
};

getCategories();

</script>

<template>
  <AuthenticatedLayout>
    <h4 class="mb-2">
      <span class="text-muted fw-light">Products / </span>Add Product
    </h4>
    <div class="row">
      <div class="col-md-8">
        <form @submit.prevent="submit">
          <div class="card mb-4">
            <div
              class="card-header d-flex justify-content-between align-items-center"
            >
              <h5 class="mb-0">Details</h5>
            </div>
            <div class="card-body">
              <div class="row mb-2">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" for="Name"
                      >Name <span class="text-danger">*</span></label
                    >
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Name"
                      v-model="form.name"
                      autofocus
                    />
                    <div class="text-danger" v-if="errors.name">
                      {{ errors.name }}
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" for="Price"
                      >Price <span class="text-danger">*</span></label
                    >
                    <input
                      type="number"
                      class="form-control"
                      placeholder="Product price"
                      v-model="form.price"
                    />
                    <div class="text-danger" v-if="errors.price">
                      {{ errors.price }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-2" v-if="categories">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="form-label" for="Category"
                      >Category</label> <span class="text-danger">*</span>
                    <Multiselect
                      v-model="form.categories"
                      :options="categories"
                      :searchable="true"
                        mode="tags"
                      label="name"
                      track-by="id"
                    />

                    <div class="text-danger" v-if="errors.categories">
                      {{ errors.categories }}
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-2">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="form-label" for="Description"
                      >Description</label
                    >
                    <textarea
                      class="form-control"
                      placeholder="Description"
                      v-model="form.description"
                    ></textarea>
                  </div>
                </div>
              </div>

              <div class="row mb-2">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" for="Status">Status</label>
                    <select class="form-control" v-model="form.status">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-label" for="image">Image</label>
                    <input
                        type="file"
                        class="form-control"
                        @change="onFileChange"
                    />
                    <div v-if="imagePreviewUrl" class="image-preview">
                        <img :src="imagePreviewUrl" alt="Preview" class="img-thumbnail mt-2"  accept="image/png, image/jpeg, image/gif" style="max-height:100px;border-radius:10px">
                    </div>
                    <div class="text-danger" v-if="errors.image">
                      {{ errors.image }}
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
                :to="{ name: 'products-index' }"
                class="btn btn-outline-secondary waves-effect"
                >Discard</router-link
              >
            </div>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
