import { ref } from 'vue'

const getCategories = () => {
    const categories = ref([])
    const error = ref(false)
    const isLoading = ref(true)

    const load = async () => {
        isLoading.value = true

        await axios
            .get('/api/categories')
            .then((response) => {
                categories.value = response.data.data
            })
            .catch(() => (error.value = true))
            .then(() => (isLoading.value = false))
    }

    return { load, categories, error, isLoading }
}

export default getCategories
