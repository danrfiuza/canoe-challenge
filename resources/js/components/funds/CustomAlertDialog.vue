<template>
  <AlertDialog :open="isOpen" @update:open="updateIsOpen">
    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle>{{ title }}</AlertDialogTitle>
        <AlertDialogDescription>
          {{ description }}
        </AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <AlertDialogCancel @click="handleCancel">{{ cancelText }}</AlertDialogCancel>
        <AlertDialogAction @click="handleConfirm">{{ confirmText }}</AlertDialogAction>
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>
</template>

<script setup lang="ts">
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'
import { useToast } from '@/components/ui/toast/use-toast'

const { toast } = useToast()

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: 'Are you absolutely sure?',
  },
  description: {
    type: String,
    default: 'This action cannot be undone.',
  },
  confirmText: {
    type: String,
    default: 'Continue',
  },
  cancelText: {
    type: String,
    default: 'Cancel',
  },
  action: {
    type: Function,
    required: true,
  },
})

const emit = defineEmits(['confirmed', 'cancelled', 'update:isOpen'])

const handleCancel = () => {
  emit('cancelled')
  emit('update:isOpen', false)
}

const handleConfirm = async () => {
  try {
    await props.action()
    toast({ title: 'Success', description: 'Action completed successfully.' })
    emit('confirmed')
  } catch (error) {
    toast({ title: 'Error', description: 'Failed to complete action.' })
  } finally {
    emit('update:isOpen', false)
  }
}

const updateIsOpen = (value: boolean) => {
  emit('update:isOpen', value)
}
</script>