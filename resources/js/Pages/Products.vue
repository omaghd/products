<script setup>
import Default from "@/Layouts/Default.vue";
import ProductsTable from "@/Components/Products/ProductsTable.vue";

import { onMounted, ref } from "vue";

const products = ref([]);
const page = ref(1);
const sortDirection = ref('desc')

const getProducts = () => {
    axios.get(`/api/products?page=${page.value}&sort=${sortDirection.value}`)
         .then(response => {
             products.value = response.data.data;
         });
}

const changePage = (p) => {
    page.value = p.split("page=")[1].split("&")[0];
    getProducts();
}

const toggleSort = () => {
    sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc';
    getProducts();
}

onMounted(() => {
    getProducts();
});
</script>

<template>
    <Default title="Products">
        <ProductsTable :products="products"
                       :sort-direction="sortDirection"
                       @change-page="changePage"
                       @toggle-sort="toggleSort"
                       @refresh="getProducts"
        />
    </Default>
</template>
