import axios from 'axios'

export const fetchFundsData = async (page = 1) => {
    try {
        const response = await axios.get('/api/funds', {
            params: {
                with: ['fundManager', 'aliases'],
                page,
            },
        })
        return response.data
    } catch (error) {
        console.error('Error fetching funds:', error)
        throw error
    }
}

export const createFund = async (fundData) => {
    try {
        const response = await axios.post('/api/funds', fundData)
        return response.data
    } catch (error) {
        console.error('Error creating fund:', error)
        throw error
    }
}

export const updateFund = async (fundId, fundData) => {
    try {
        const response = await axios.put(`/api/funds/${fundId}`, fundData)
        return response.data
    } catch (error) {
        console.error('Error updating fund:', error)
        throw error
    }
}

export const deleteFundData = async (fundId) => {
    try {
        const response = await axios.delete(`/api/funds/${fundId}`)
        return response.data
    } catch (error) {
        console.error('Error deleting fund:', error)
        throw error
    }
}