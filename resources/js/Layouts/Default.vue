<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, } from '@headlessui/vue'

import Bars3Icon from '@heroicons/vue/24/outline/Bars3Icon.js'
import XMarkIcon from '@heroicons/vue/24/outline/XMarkIcon.js'
import ShoppingCartIcon from '@heroicons/vue/24/outline/ShoppingCartIcon.js'
import ShoppingBagIcon from '@heroicons/vue/24/outline/ShoppingBagIcon.js'

defineProps({
    title: {
        type: String,
        required: true,
    },
})
</script>

<template>
    <div>
        <Disclosure as="div" class="relative bg-sky-700 pb-32 overflow-hidden" v-slot="{ open }">
            <nav
                :class="[open ? 'bg-sky-900' : 'bg-transparent', 'relative z-10 border-b border-teal-500 border-opacity-25 lg:bg-transparent lg:border-none']">
                <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
                    <div class="relative h-16 flex items-center justify-between lg:border-b lg:border-sky-800">
                        <div class="px-2 flex items-center lg:px-0">
                            <div class="flex-shrink-0">
                                <ShoppingCartIcon class="h-8 w-8 text-teal-400" />
                            </div>
                        </div>
                        <div class="flex-1 px-2 flex justify-center lg:ml-6 lg:justify-end">
                        </div>
                        <div class="flex lg:hidden">
                            <DisclosureButton
                                class="p-2 rounded-md inline-flex items-center justify-center text-sky-200 hover:text-white hover:bg-sky-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                                <span class="sr-only">Open main menu</span>
                                <Bars3Icon v-if="!open" class="block flex-shrink-0 h-6 w-6" aria-hidden="true" />
                                <XMarkIcon v-else class="block flex-shrink-0 h-6 w-6" aria-hidden="true" />
                            </DisclosureButton>
                        </div>
                        <div class="hidden lg:block lg:ml-4">
                        </div>
                    </div>
                </div>

                <DisclosurePanel class="bg-sky-900 lg:hidden">
                </DisclosurePanel>
            </nav>
            <div aria-hidden="true"
                 :class="[open ? 'bottom-0' : 'inset-y-0', 'absolute inset-x-0 left-1/2 transform -translate-x-1/2 w-full overflow-hidden lg:inset-y-0']">
                <div class="absolute inset-0 flex">
                    <div class="h-full w-1/2" style="background-color: #0a527b" />
                    <div class="h-full w-1/2" style="background-color: #065d8c" />
                </div>
                <div class="relative flex justify-center">
                    <svg class="flex-shrink-0" width="1750" height="308" viewBox="0 0 1750 308"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M284.161 308H1465.84L875.001 182.413 284.161 308z" fill="#0369a1" />
                        <path d="M1465.84 308L16.816 0H1750v308h-284.16z" fill="#065d8c" />
                        <path d="M1733.19 0L284.161 308H0V0h1733.19z" fill="#0a527b" />
                        <path d="M875.001 182.413L1733.19 0H16.816l858.185 182.413z" fill="#0a4f76" />
                    </svg>
                </div>
            </div>
            <header class="relative py-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-white">{{ title }}</h1>
                </div>
            </header>
        </Disclosure>

        <main class="relative -mt-32">
            <div class="max-w-screen-xl mx-auto pb-6 px-4 sm:px-6 lg:pb-16 lg:px-8">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="divide-y divide-gray-200 lg:grid lg:grid-cols-12 lg:divide-y-0 lg:divide-x">
                        <aside class="py-6 lg:col-span-3">
                            <nav class="space-y-1">
                                <div
                                    class="group border-l-4 px-3 py-2 flex items-center text-sm font-medium bg-teal-50 border-teal-500 text-teal-700 hover:bg-teal-50 hover:text-teal-700">
                                    <ShoppingBagIcon
                                        class="text-teal-500 group-hover:text-teal-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6" />
                                    <span class="truncate">
                                        Products
                                    </span>
                                </div>
                            </nav>
                        </aside>

                        <div class="divide-y divide-gray-200 lg:col-span-9">
                            <div class="py-6 px-4 sm:p-6 lg:pb-8">
                                <div>
                                    <h2 class="text-lg leading-6 font-medium text-gray-900">{{ title }}</h2>
                                    <slot></slot>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
