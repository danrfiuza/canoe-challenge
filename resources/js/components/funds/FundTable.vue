<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead class="w-[100px]">ID</TableHead>
                <TableHead>Name</TableHead>
                <TableHead>Start Year</TableHead>
                <TableHead>Fund Manager</TableHead>
                <TableHead>Actions</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="fund in funds" :key="fund.id">
                <TableCell class="font-medium">{{ fund.id }}</TableCell>
                <TableCell>{{ fund.name }}</TableCell>
                <TableCell>{{ fund.start_year }}</TableCell>
                <TableCell>{{ fund.fund_manager.name }}</TableCell>
                <TableCell>
                    <DropDownActions :actions="getActions(fund)" />
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>
</template>

<script setup lang="ts">
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import { Fund } from '@/types';
import DropDownActions from './DropDownActions.vue';

const props = defineProps<{
    funds: Fund[];
}>();

const emit = defineEmits(['refresh', 'edit', 'delete'])

const getActions = (fund: Fund) => [
    {
        label: 'Edit Fund',
        callback: () => editFund(fund),
    },
    {
        label: 'Delete Fund',
        callback: () => deleteFund(fund),
    },
];

const editFund = (fund: Fund) => {
   emit('edit', fund);
};

const deleteFund = (fund: Fund) => {
    emit('delete', fund);
};
</script>