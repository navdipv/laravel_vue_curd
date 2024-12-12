<script setup>

const props = defineProps([
    "label",
    "form",
    "formSubmit",
    "orderBy"
]);

const sortTable = (columnName, columnOrder) => {
    props.form.orderBy = columnName;
    props.form.sortAs = columnOrder;
    props.formSubmit();
};
</script>
<template>
    <div class="d-flex justify-content-between gap-2">
        {{ label }}
        <font-awesome-icon v-if="form.orderBy !== orderBy" @click="sortTable(orderBy, 'desc')"
            :icon="['fass', 'arrow-up-arrow-down']" size="lg" />
        <font-awesome-icon v-else-if="form.sortAs === 'asc'" @click="sortTable(orderBy, 'desc')"
            :icon="['fass', 'arrow-up']" size="lg" color="#343965" />
        <font-awesome-icon v-else @click="sortTable(orderBy, 'asc')" :icon="['fass', 'arrow-down']" size="lg"
            color="#343965" />
    </div>
</template>
