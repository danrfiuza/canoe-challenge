<template>
    <form @submit="onSubmit">
        <Card>
            <CardContent>
                <FormField v-slot="{ componentField }" name="name">
                    <FormItem>
                        <FormLabel>Name</FormLabel>
                        <Input v-bind="componentField" />
                        <FormDescription>
                            Inform the Fund name
                        </FormDescription>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="start_year">
                    <FormItem>
                        <FormLabel>Start year</FormLabel>
                        <Input v-bind="componentField" type="number" />
                        <FormDescription>
                            Inform the start year of the Fund
                        </FormDescription>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="fund_manager_id">
                    <FormItem>
                        <FormLabel>Fund Manager</FormLabel>

                        <Select v-bind="componentField">
                            <FormControl>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select a Fund Manager" />
                                </SelectTrigger>
                            </FormControl>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem v-for="{ id, name } in fundManagers" :key="id" :value="id">
                                        {{ name }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <FormDescription>
                        </FormDescription>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <Card class="mt-4">
                    <CardHeader>
                        <div class="flex justify-between items-center">
                            <CardTitle>Fund Aliases</CardTitle>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="max-h-64 h-64 overflow-y-auto border rounded-lg p-4">
                            <div v-for="(alias, index) in aliases" :key="index" class="mb-4">
                                <FormField v-slot="{ componentField }" :name="`aliases.${index}.alias`">
                                    <FormItem>
                                        <div class="flex w-full">
                                            <Input v-bind="componentField" v-model="aliases[index].alias" />
                                            <Button class="ml-4" variant="outline"
                                                @click.prevent="removeAlias(alias, index)">
                                                Remove
                                            </Button>
                                        </div>
                                        <FormMessage />
                                    </FormItem>
                                </FormField>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <Button type="button" variant="outline" @click="addAlias">Add Alias</Button>
                        </div>
                    </CardContent>
                </Card>
            </CardContent>
            <CardFooter class="flex justify-end">
                <Button type="submit" class="mt-4">
                    Save changes
                </Button>
            </CardFooter>
        </Card>
    </form>
</template>

<script setup lang="ts">
    import { ref, onMounted, watch } from 'vue'
    import { fetchFundManagersData } from '../../api/fund-managers'
    import { toTypedSchema } from '@vee-validate/zod'
    import { useForm } from 'vee-validate'
    import * as z from 'zod'
    import {
        FormControl,
        FormDescription,
        FormField,
        FormItem,
        FormLabel,
        FormMessage,
    } from '@/components/ui/form'
    import { Input } from '@/components/ui/input'
    import { createFund, updateFund } from '@/api/funds'
    import { deleteFundAliasData } from '@/api/fund-aliases'
    import {
        Select,
        SelectContent,
        SelectGroup,
        SelectItem,
        SelectTrigger,
        SelectValue,
    } from '@/components/ui/select'
    import { Button } from '@/components/ui/button'
    import { useToast } from '@/components/ui/toast'
    import {
        Card,
        CardContent,
        CardFooter,
        CardHeader,
        CardTitle,
    } from '@/components/ui/card'
    import { FundAlias } from '@/types'

    const { toast } = useToast()

    const props = defineProps({
        fund: {
            type: Object,
            default: () => ({}),
        },
    })

    const aliases = ref<FundAlias[]>(props.fund.aliases ? props.fund.aliases.map((a: FundAlias) => ({ ...a })) : [])

    const addAlias = () => {
        aliases.value.push({ id: null, alias: '', fund_id: props.fund.id || null })
    }

    const removeAlias = (alias: FundAlias, index: number) => {
        if (alias.id) {
            deleteFundAliasData(alias.id)
        }
        aliases.value.splice(index, 1)
    }

    const formSchema = toTypedSchema(z.object({
        name: z.string().nonempty('Please enter a name.'),
        start_year: z.number().min(1900, 'Please enter a valid year.'),
        fund_manager_id: z.number().min(1, 'Please select a fund manager.'),
        aliases: z.array(z.object({
            alias: z.string().nonempty('Alias cannot be empty'),
        })).optional(),
    }))

    const { handleSubmit, setValues } = useForm({
        validationSchema: formSchema,
        initialValues: props.fund,
    })

    const emit = defineEmits(['fundSubmitted'])

    const onSubmit = handleSubmit((values) => {
        values.aliases = aliases.value
        const request = props.fund.id
            ? updateFund(props.fund.id, values)
            : createFund(values)

        request
            .then(response => {
                toast({ title: 'Success', description: 'Fund saved successfully.' })
                emit('fundSubmitted', response.data);
            })
            .catch(error => {
                console.error('Error saving fund:', error);
            });
    })

    const fundManagers = ref([])

    onMounted(async () => {
        try {
            const response = await fetchFundManagersData()
            fundManagers.value = response.data.data
        } catch (error) {
            console.error('Error loading fund managers:', error)
        }
    })

    watch(() => props.fund, (newFund) => {
        setValues(newFund)
        aliases.value = newFund.aliases ? newFund.aliases.map((a: FundAlias) => ({ ...a })) : []
    }, { immediate: true })
</script>
