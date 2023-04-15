<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Leave a Message</h1>
        <Link href="/guestbook" replace>Home</Link>
        <form @submit.prevent="submit" class="max-w-lg mx-auto">
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
                <label for="message" class="block text-gray-700 font-bold mb-2">Message:</label>
                <textarea id="message" v-model="form.message" :class="(errors.email ? ' border-red-500 ' : '') + ' shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'"></textarea>
                <p v-if="errors.message" class="text-red-500 text-xs italic">{{ errors.message }}</p>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-bold mb-2">Image:</label>
                <input id="image" type="file" @input="form.image = $event.target.files[0]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                <p v-if="errors.image" class="text-red-500 text-xs italic">{{ errors.image }}</p>
            </div>
            <div class="mb-4">
                <label for="captcha" class="block text-gray-700 font-bold mb-2">CAPTCHA:</label>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
        </form>
    </div>
</template>

<script setup>

import { useForm, Link } from '@inertiajs/vue3'

defineProps({ errors: Object })

const form = useForm({
    name: '',
    email: '',
    message: '',
    image: null,
})

const submit = () => {
    form.post('/guestbook')
}
</script>
