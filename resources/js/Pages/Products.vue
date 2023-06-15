<script setup>
import Default from "@/Layouts/Default.vue";
import ProductsTable from "@/Components/Products/ProductsTable.vue";

import getProducts from "@/Composables/Products/getProducts";

import { onMounted, ref } from "vue";

const page = ref(1);
const sortDirection = ref('desc')

const { products, load: loadProducts } = getProducts(page, sortDirection);

const changePage = (p) => {
    page.value = p.split("page=")[1].split("&")[0];
    loadProducts();
}

const toggleSort = () => {
    sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc';
    loadProducts();
}

onMounted(() => {
    loadProducts();
});
</script>

<template>
    <Default title="Products">
        <ProductsTable :products="products"
                       :sort-direction="sortDirection"
                       @change-page="changePage"
                       @toggle-sort="toggleSort"
                       @refresh="loadProducts"
        />
    </Default>
</template>
