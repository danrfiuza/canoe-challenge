import axios from 'axios'

export const deleteFundAliasData = async (id) => {
    try {
        const response = await axios.delete(`/api/fund-alias/${id}`)
        return response.data
    } catch (error) {
        console.error('Error deleting fund alias:', error)
        throw error
    }
}