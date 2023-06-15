<script setup>
defineProps({
    links: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <div>
        <Component
            :is="link.url ? 'button' : 'span'"
            v-for="link in links"
            :href="link.url"
            v-html="link.label"
            @click="$emit('changePage', link.url)"
            class="px-1 hover:text-teal-600"
            :class="{ 'text-gray-500': ! link.url, 'font-semibold text-teal-600' : link.active }"
        />
    </div>
</template>
