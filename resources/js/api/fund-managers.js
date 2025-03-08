import axios from 'axios'

export const fetchFundManagersData = async () => {
    try {
        const response = await axios.get('/api/fund-managers')
        return response.data
    } catch (error) {
        console.error('Error fetching fund managers:', error)
        throw error
    }
}