<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Messages</h1>
        <Link href="/guestbook/create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <span>Create message</span>
        </Link>
        <table class="table-auto w-full">
            <thead>
            <tr>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Message</th>
                <th class="px-4 py-2 text-left">Created At</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="message in rows" :key="message.id">
                <td class="border px-4 py-2">
                    <span>{{ message.name }} &nbsp;</span>
                    <a :href="'mailto:' + message.email">{{ message.email }}</a>
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
    </div>
</template>

<script setup>

import { computed } from "vue";
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps(['messages', 'userIp', 'editMinutes'])
const rows = computed(() => {
    const res = []
    for(const message of props.messages){
        const editExpired = (((new Date() - new Date(message.created_at)) / 1000 / 60) > props.editMinutes )

        res.push({
            id: message.id,
            name: message.name,
            email: message.email,
            image: message.image,
            message: message.message,
            created_at: message.created_at,
            edited_at: message.edited_at,
            editable: (message.ip === props.userIp && !editExpired),
        })
    }

    return res
})

const deleteMessage = (message) => {
    const form = useForm({})
    if (confirm('Are you sure you want to delete this message?')) {
        form.delete(`/guestbook/${message.id}`)
    }
}
</script>
