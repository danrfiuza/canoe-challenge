<template>
    <div class="max-w-10xl h-2/4 mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <Card>
            <CardHeader>
                <CardTitle>List of Funds</CardTitle>
            </CardHeader>
            <CardContent>
                <FundDialog :fund="selectedFund" :isOpen="isDialogOpen" @fundSubmitted="onFundSubmitted"
                    @update:isOpen="isDialogOpen = $event">
                    <Button variant="outline">Add Fund</Button>
                </FundDialog>
                <FundTable :funds="funds" @edit="editFund" @delete="deleteFund" @refresh="fetchFunds" />
            </CardContent>
            <CardFooter>
                <Pagination :currentPage="currentPage" :totalItems="totalItems" :itemsPerPage="itemsPerPage"
                    @pageChanged="fetchFunds" />
            </CardFooter>
        </Card>
        <Toaster />
        <CustomAlertDialog :isOpen="isAlertDialogOpen" @update:isOpen="isAlertDialogOpen = $event" title="Delete Fund"
            description="Are you sure you want to delete this fund? This action cannot be undone." confirmText="Delete"
            cancelText="Cancel" :action="confirmDelete" @confirmed="fetchFunds(currentPage)"
            @cancelled="isAlertDialogOpen = false" />
    </div>
</template>

<script setup lang="ts">
    import { ref, onMounted, watch } from 'vue'
    import {
        Card,
        CardContent,
        CardFooter,
        CardHeader,
        CardTitle,
    } from '@/components/ui/card'
    import FundDialog from '@/components/funds/FundDialog.vue'
    import FundTable from '@/components/funds/FundTable.vue'
    import Pagination from '@/components/funds/CustomPagination.vue'
    import CustomAlertDialog from '@/components/funds/CustomAlertDialog.vue'
    import { fetchFundsData, deleteFundData } from '@/api/funds'
    import { Fund } from '@/types'
    import { useToast, Toaster } from '@/components/ui/toast'
    import Button from '@/components/ui/button/Button.vue'
    const { toast } = useToast()
    const funds = ref([])
    const currentPage = ref(1)
    const totalItems = ref(0)
    const itemsPerPage = ref(10)
    const selectedFund = ref<Fund>({
        id: 0,
        name: '',
        start_year: 0,
        fund_manager: {
            name: ''
        }
    })
    const isDialogOpen = ref(false)
    const isAlertDialogOpen = ref(false)

    const fetchFunds = async (page = 1) => {
        reset()
        try {
            const { data: response } = await fetchFundsData(page)
            const { data, meta } = response
            funds.value = data
            currentPage.value = meta.current_page
            totalItems.value = meta.total
            itemsPerPage.value = meta.per_page
        } catch (error) {
            console.error('Error fetching funds:', error)
        }
    }

    const onFundSubmitted = (fund: Fund) => {
        fetchFunds(currentPage.value)
        isDialogOpen.value = false
    }

    const editFund = (fund: Fund) => {
        selectedFund.value = fund
        isDialogOpen.value = true
    }

    const deleteFund = (fund: Fund) => {
        selectedFund.value = fund
        isAlertDialogOpen.value = true
    }

    const confirmDelete = async () => {
        try {
            await deleteFundData(selectedFund.value.id)
            toast({ title: 'Success', description: 'Fund deleted successfully.' })
        } catch (error) {
            console.error('Error deleting fund:', error)
            toast({ title: 'Error', description: 'Failed to delete fund.' })
        }
    }

    const reset = () => {
        selectedFund.value = {
            id: 0,
            name: '',
            start_year: 0,
            fund_manager: {
                name: ''
            }
        }
    }

    watch(isDialogOpen, (newVal) => {
        if (!newVal) {
            reset()
        }
    })

    watch(isAlertDialogOpen, (newVal) => {
        if (!newVal) {
            reset()
        }
    })

    onMounted(() => {
        fetchFunds(currentPage.value)
    })

    window.Echo.channel('fund-duplicity')
        .listen('DuplicatedFundWarning', (e: any) => {
            console.log(e);
            toast({ title: 'Warning', description: e.message })
        });
</script>