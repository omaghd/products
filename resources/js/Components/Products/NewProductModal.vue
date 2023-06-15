<script setup>
import { Dialog, DialogOverlay, TransitionChild, TransitionRoot } from '@headlessui/vue'

import XMarkIcon from '@heroicons/vue/24/outline/XMarkIcon.js'

import FormInput from "@/Components/Forms/FormInput.vue";
import FormTextarea from "@/Components/Forms/FormTextarea.vue";
import FormLabel from "@/Components/Forms/FormLabel.vue";
import Errors from "@/Components/Forms/Errors.vue";

import { onMounted, ref } from "vue";

import { useToast } from "vue-toastification";

import useProducts from "@/Composables/Products/useProducts.js";
import Autocomplete from "@/Components/Forms/Autocomplete.vue";
import getCategories from "@/Composables/Categories/getCategories.js";

defineProps({
    open: {
        type: Boolean,
        required: true,
    }
})

const emit = defineEmits(['close', 'success'])

const name = ref('')
const description = ref('')
const price = ref('')
const image = ref(null)
const categories = ref([])

const previewImage = ref("https://fakeimg.pl/500x500/cccccc/909090?text=YouCan")

const onFileChange = (e) => {
    const file = e.target.files[0]
    previewImage.value = URL.createObjectURL(file)
    image.value = file
}

const resetInput = ref(false)

const { load, categories: categoriesOptions } = getCategories()

const toast = useToast()

const reset = () => {
    resetInput.value = true

    name.value = ''
    description.value = ''
    price.value = ''
    image.value = null
    categories.value = []
    previewImage.value = "https://fakeimg.pl/500x500/cccccc/909090?text=YouCan"
}

const { create, isLoading, isSuccess, errors, message } = useProducts()

const onSubmit = async () => {
    if (isLoading.value) return

    const product = {
        name: name.value,
        description: description.value,
        price: price.value,
        image: image.value,
        categories: categories.value?.map((category) => category.id)
    }

    await create(product)

    if (isSuccess.value) {
        toast.success(message.value)

        reset()

        emit('success')

        emit('close')
    } else {
        toast.error(message.value)
    }
}

onMounted(() => {
    load()
})
</script>

<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="fixed z-10 inset-0 overflow-y-auto" @close="$emit('close')">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                                 enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                                 leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <TransitionChild as="template" enter="ease-out duration-300"
                                 enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                                 leave-from="opacity-100 translate-y-0 sm:scale-100"
                                 leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div
                        class="relative inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left  shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                        <div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
                            <button type="button"
                                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500"
                                    @click="open = false">
                                <span class="sr-only">Close</span>
                                <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                            </button>
                        </div>
                        <h2 class="text-lg leading-6 font-medium text-gray-900">
                            New Product
                        </h2>

                        <form class="mt-6" @submit.prevent="onSubmit">
                            <FormInput id="name"
                                       type="text"
                                       label="Name"
                                       placeholder="Name"
                                       @change="(value) => (name = value)"
                                       :errors="errors?.name"
                                       :reset="resetInput"
                                       @reset="() => (resetInput = false)"
                            />

                            <FormTextarea id="description"
                                          label="Description"
                                          placeholder="Description"
                                          class="mt-3"
                                          @change="(value) => (description = value)"
                                          :errors="errors?.description"
                                          :reset="resetInput"
                                          @reset="() => (resetInput = false)"
                            />

                            <FormInput id="price"
                                       type="number"
                                       label="Price"
                                       placeholder="Price"
                                       class="mt-3"
                                       @change="(value) => (price = value)"
                                       :errors="errors?.price"
                                       :reset="resetInput"
                                       @reset="() => (resetInput = false)"
                            />

                            <div class="mt-3">
                                <FormLabel id="image" text="Image" />
                                <div class="flex items-center gap-3">
                                    <input type="file" class="mt-3 flex-1" id="image" @change="onFileChange" />
                                    <img :src="previewImage"
                                         alt=""
                                         class="w-20 h-20 rounded-md" />
                                </div>
                                <Errors :errors="errors?.image" />
                            </div>

                            <Autocomplete :options="categoriesOptions"
                                          label="Categories"
                                          null-text="Select categories"
                                          :errors="errors?.categories"
                                          @update="(value) => (categories = value)"
                            />

                            <button type="submit"
                                    class="mt-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 disabled:opacity-50"
                                    :disabled="isLoading">
                                Create
                            </button>
                        </form>
                    </div>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
