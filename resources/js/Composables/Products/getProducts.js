import { ref } from 'vue'

const getProducts = (page, sort) => {
    const products = ref([])
    const error = ref(false)
    const isLoading = ref(true)

    const load = async () => {
        isLoading.value = true

        await axios
            .get(`/api/products?page=${page.value}&sort=${sort.value}`)
            .then((response) => {
                products.value = response.data.data
            })
            .catch(() => (error.value = true))
            .then(() => (isLoading.value = false))
    }

    return { load, products, error, isLoading }
}

export default getProducts
