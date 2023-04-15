<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Messages</h1>
        <Link href="/guestbook/create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <span>Create message</span>
        </Link>
        <table class="table-fixed w-full">
            <thead>
            <tr>
                <th class="w-2/12 px-4 py-2 text-left cursor-pointer">
                    <button @click="sort('name')" class="flex items-center">
                        <span>Name</span>
                        <svg v-if="orderBy === 'name'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="ml-2 h-4 w-4">
                            <path v-if="order === 'asc'" d="M10 3l-7 9h14z" />
                            <path v-else-if="order === 'desc'" d="M10 17l-7-9h14z" />
                        </svg>
                    </button>

                </th>
                <th class="w-6/12 px-4 py-2 text-left">Message</th>
                <th class="w-2/12 px-4 py-2 text-left cursor-pointer">
                    <button @click="sort('created_at')" class="flex items-center">
                        <span>Created At</span>
                        <svg v-if="orderBy === 'created_at'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="ml-2 h-4 w-4">
                            <path v-if="order === 'asc'" d="M10 3l-7 9h14z" />
                            <path v-else-if="order === 'desc'" d="M10 17l-7-9h14z" />
                        </svg>
                    </button>
                </th>
                <th class="w-2/12 px-4 py-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="message in rows" :key="message.id">
                <td class="border px-4 py-2">
                    <p>{{ message.name }}</p>
                    <p>
                        <a :href="'mailto:' + message.email">{{ message.email }}</a>
                    </p>
                </td>
                <td class="border px-4 py-2">
                    {{ message.message }}
                    <a v-if="message.image" :href="'storage/' + message.image" target="_blank">
                        <img :src="'storage/' + message.image" class="max-w-md" width="200">
                    </a>
                    <p v-if="message.edited_at" class="italic">Edited: {{ message.edited_at }}</p>
                </td>
                <td class="border px-4 py-2">{{ message.created_at }}</td>
                <td class="border px-4 py-2">
                    <Link v-if="message.editable" :href="`/guestbook/${message.id}/edit`" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Edit
                    </Link>
                    &nbsp;
                    <button v-if="message.editable" @click="deleteMessage(message)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1.5 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="flex justify-center">
            <pagination class="flex mt-6" :links="messages.links" />
        </div>
    </div>
</template>

<script setup>

import { computed, ref } from "vue";
import { Link, useForm, router } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps(['messages', 'userIp', 'editMinutes', 'orderBy', 'order'])
const orderBy = ref(props.orderBy)
const order = ref(props.order)
const rows = computed(() => {
    const res = []
    for(const message of props.messages.data){
        const editExpired = (((new Date() - new Date(message.created_at)) / 1000 / 60) > props.editMinutes )

        res.push({
            id: message.id,
            name: message.name,
            email: message.email,
            image: message.image,
            message: message.message,
            created_at: new Date(message.created_at).toLocaleString("lv-Lv"),
            edited_at: message.edited_at ? new Date(message.edited_at).toLocaleString("lv-Lv") : null,
            editable: (message.ip === props.userIp && !editExpired),
        })
    }

    return res
})

const sort = (field) => {
    orderBy.value = field
    order.value = order.value === 'asc' ? 'desc' : 'asc'
    router.get(`/guestbook?orderBy=${orderBy.value}&order=${order.value}`)
}

const deleteMessage = (message) => {
    const form = useForm({})
    if (confirm('Are you sure you want to delete this message?')) {
        form.delete(`/guestbook/${message.id}`)
    }
}
</script>
