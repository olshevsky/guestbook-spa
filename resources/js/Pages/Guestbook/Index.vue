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
            <tr v-for="message in messages" :key="message.id">
                <td class="border px-4 py-2">
                    <span>{{ message.name }} &nbsp;</span>
                    <a :href="'mailto:' + message.email">{{ message.email }}</a>
                </td>
                <td class="border px-4 py-2">
                    {{ message.message }}
                    <a href="" target="_blank">
                        <img :src="message.image" class="max-w-md">
                    </a>
                    <p class="italic">Edited: 01.01.2023 08:00:00</p>
                </td>
                <td class="border px-4 py-2">{{ message.created_at }}</td>
                <td class="border px-4 py-2">
                    <button @click="editMessage(message)" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">Edit</button>
                    <button @click="deleteMessage(message)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>

import { Link } from '@inertiajs/vue3'

const props = defineProps(['messages']);

const editMessage = (message) => {
    alert('edit')
}

const deleteMessage = (message) => {
    alert('delete')
}
</script>
