<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Edit User</h1>
        <div class="mb-6">
            <Link href="/user" replace>Users list</Link>
        </div>
        <form method="POST" @submit.prevent="submit" class="max-w-lg mx-r">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                <input type="text" id="name" v-model="form.name" :class="(errors.name ? ' border-red-500' : '') + ' shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'">
                <p v-if="errors.name" class="text-red-500 text-xs italic">{{ errors.name }}</p>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" id="email" v-model="form.email" :class="(errors.email ? ' border-red-500 ' : '') + ' shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'">
                <p v-if="errors.email" class="text-red-500 text-xs italic">{{ errors.email }}</p>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                <input type="password" id="password" v-model="form.password" :class="(errors.password ? ' border-red-500 ' : '') + ' shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'">
                <p v-if="errors.password" class="text-red-500 text-xs italic">{{ errors.password }}</p>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
        </form>
    </div>
</template>

<script setup>

import { useForm, Link, router } from '@inertiajs/vue3'

const props = defineProps(['user', 'errors'])

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: null,
})

const submit = () => {
    router.post(`/user/${props.user.id}`, {
        _method: 'put',
        name: form.name,
        email: form.email,
        password: form.password,
    })
}
</script>
