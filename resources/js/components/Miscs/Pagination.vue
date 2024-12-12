<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    data: Object,
    pageMethod: Function
});

const currentPage = computed(() => props.data.data.current_page);
const lastPage = computed(() => props.data.data.last_page);

const visiblePages = computed(() => {
    const pages = [];
    const startPage = Math.max(1, currentPage.value - 2);
    const endPage = Math.min(lastPage.value, currentPage.value + 2);

    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }
    return pages;
});

const changePage = (page) => {
    if (page > 0 && page <= lastPage.value) {
        props.pageMethod(page);
    }
};
</script>

<template>
    <div class="row">
        <div class="col-md-6">
            <div v-if="props.data.data.total > 0">
                Showing {{ props.data.data.from }} to {{ props.data.data.to }} of {{ props.data.data.total }} entries
            </div>
            <div v-else>
                Showing 0 to 0 of 0 entries
            </div>
        </div>
        <div class="col-md-6">
            <nav aria-label="Page navigation" class="mx-2" style="cursor: pointer">
                <ul class="pagination justify-content-end mt-2">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <a class="page-link" @click.prevent="changePage(currentPage - 1)">Previous</a>
                    </li>
                    <li v-if="currentPage > 3" class="page-item">
                        <a class="page-link" @click.prevent="changePage(1)">1</a>
                        <span v-if="currentPage > 4" class="page-link">...</span>
                    </li>
                    <li class="page-item" v-for="page in visiblePages" :key="page" :class="{ active: currentPage === page }">
                        <a class="page-link" @click.prevent="changePage(page)">{{ page }}</a>
                    </li>
                    <li v-if="currentPage < lastPage - 3"><span class="page-link">...</span></li>
                    <li v-if="currentPage < lastPage - 2" class="page-item">
                        <a class="page-link" @click.prevent="changePage(lastPage)">{{ lastPage }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === lastPage }">
                        <a class="page-link" @click.prevent="changePage(currentPage + 1)">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
