<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Users</h1>
        <div class="float-right">
            <div v-if="props.user">
                User: {{ user.name }}
                <button @click.prevent="logout" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Logout
                </button>
            </div>
            <div v-else>
                <button @click.prevent="login" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Login
                </button>
            </div>
        </div>
        <div class="mb-6">
            <Link href="/user/create" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                <span>Create user</span>
            </Link>
            <Link href="/guestbook" class="">
                <span>Guestbook</span>
            </Link>
        </div>
        <table class="table-fixed w-full">
            <thead>
            <tr>
                <th class="w-2/12 px-4 py-2 text-left cursor-pointer">Name</th>
                <th class="w-6/12 px-4 py-2 text-left">Email</th>
                <th class="w-6/12 px-4 py-2 text-left">Admin</th>
                <th class="w-2/12 px-4 py-2 text-left">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="user in rows" :key="user.id">
                <td class="border px-4 py-2">
                    <p>{{ user.name }}</p>
                </td>
                <td class="border px-4 py-2">
                    <p>{{ user.email }}</p>
                </td>
                <td class="border px-4 py-2">
                    <p>{{ user.isAdmin }}</p>
                </td>
                <td class="border px-4 py-2">
                    <Link :href="`/user/${user.id}/edit`" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Edit
                    </Link>
                    &nbsp;
                    <button @click="deleteMessage(user)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1.5 px-4 rounded focus:outline-none focus:shadow-outline">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="flex justify-center">
            <pagination class="flex mt-6" :links="users.links" />
        </div>
    </div>
</template>

<script setup>

import { computed, ref } from "vue";
import { Link, useForm, router } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps(['users', 'isAdmin', 'user'])

const rows = computed(() => {
    const res = []
    for(const user of props.users.data){
        res.push({
            id: user.id,
            name: user.name,
            email: user.email,
            isAdmin: user.is_admin ? 'Yes' : 'No'
        })
    }

    return res
})

const deleteMessage = (user) => {
    const form = useForm({})
    if (confirm('Are you sure you want to delete this user?')) {
        form.delete(`/user/${user.id}`)
    }
}

const logout = () => {
    router.post(`/logout`)
}

const login = () => {
    router.get(`/login `)
}
</script>
