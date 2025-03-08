<template>
    <Dialog :open="isOpen" @update:open="updateIsOpen">
        <DialogTrigger as-child>
            <slot></slot>
        </DialogTrigger>
        <DialogScrollContent>
            <DialogContent class="w-full h-full max-w-10xl">
                <DialogHeader>
                    <DialogTitle>{{ fund.id ? 'Edit Fund' : 'Add Fund' }}</DialogTitle>
                    <DialogDescription>
                        Make changes to your fund here. Click save when you're done.
                    </DialogDescription>
                </DialogHeader>
                <FundForm :fund="fund" @fundSubmitted="onFundSubmitted" />
            </DialogContent>
        </DialogScrollContent>
    </Dialog>
</template>

<script setup lang="ts">
import { watch } from 'vue'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogScrollContent
} from '@/components/ui/dialog'
import FundForm from './FundForm.vue'
import { Fund } from '@/types'

const props = defineProps({
    fund: {
        type: Object,
        default: () => ({}),
    },
    isOpen: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['fundSubmitted', 'update:isOpen'])

const onFundSubmitted = (fund: Fund) => {
    emit('fundSubmitted', fund)
    emit('update:isOpen', false)
}

const updateIsOpen = (value: boolean) => {
    emit('update:isOpen', value)
}

watch(() => props.isOpen, (newVal) => {
    if (!newVal) {
        emit('update:isOpen', false)
    }
})
</script>