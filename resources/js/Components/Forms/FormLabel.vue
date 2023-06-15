<script setup>
defineProps({
    id: {
        type: String,
        required: false,
    },
    text: {
        type: String,
        required: true,
    },
})
</script>

<template>
    <label :for="id" class="mb-1 block text-sm font-medium text-gray-700">
        {{ text }}
    </label>
</template>
