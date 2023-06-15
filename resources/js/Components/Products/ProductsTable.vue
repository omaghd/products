<script setup>
import Pagination from "@/Components/Common/Pagination.vue";

import ChevronDownIcon from '@heroicons/vue/24/outline/ChevronDownIcon.js'

defineProps({
    products: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Products</h1>
            <p class="mt-2 text-sm text-gray-700">A list of all the products in your organization.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button type="button"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-teal-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 sm:w-auto">
                Add Product
            </button>
        </div>
    </div>
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                    Product
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    <a href="#" class="group inline-flex">
                                        Price
                                        <span
                                            class="ml-2 flex-none rounded bg-gray-200 text-gray-900 group-hover:bg-gray-300">
                                            <ChevronDownIcon class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                    </a>
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="product in products.data" :key="product.id">
                                <td class="px-3 py-4 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <img :src="product.image" alt="" class="flex-none w-16 h-16 rounded-lg" />
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                                            <div class="text-sm text-gray-500">{{ product.description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ product.price }}</td>

                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="#" class="text-teal-600 hover:text-teal-900">Edit</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <Pagination :links="products.links" @change-page="(val) => $emit('changePage', val)" />
    </div>
</template>
