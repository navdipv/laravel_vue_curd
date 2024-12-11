<script setup>
const props = defineProps({
    links: Object,
    changePageFunction: Function
});
</script>

<template>
    <nav aria-label="Page navigation" class="mx-2">
        <ul class="pagination justify-content-center  mt-2">
            <template v-for="link in links " :key="link.label">
                <li v-if="link.url != null" class="page-item" :class="link.active == true ? 'active' : ''">
                    <a @click="changePageFunction(link.url)" class="page-link" v-html="link.label"></a>
                </li>
                <li v-if="link.url == null" class="page-item">
                    <a class="page-link" v-html="link.label"></a>
                </li>
            </template>
        </ul>
    </nav>
</template>

<style scoped>
.page-link {
    cursor: pointer;
}
</style>
