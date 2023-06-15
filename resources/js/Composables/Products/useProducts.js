import { ref } from 'vue'


const product = ref({})
const errors = ref([])
const message = ref('')
const isLoading = ref(false)
const isSuccess = ref(false)

const create = async (newProduct) => {
    errors.value = []
    isLoading.value = true

    const headers = { 'Content-Type': 'multipart/form-data' }

    await axios
        .post('/api/products', newProduct, { headers })
        .then((response) => {
            message.value = response.data.message

            product.value = response.data.data

            isSuccess.value = true
        })
        .catch((error) => {
            console.log(error)
            errors.value = error.response?.data.errors

            message.value = 'Please check the errors'
        })
        .then(() => {
            isLoading.value = false
        })
}

const useProducts = () => {
    return {
        errors,
        isLoading,
        isSuccess,
        message,
        product,
        create,
    }
}

export default useProducts
