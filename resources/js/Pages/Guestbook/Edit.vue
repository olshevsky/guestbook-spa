<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Edit message</h1>
        <div class="mb-6">
            <Link href="/guestbook" replace>Message list</Link>
        </div>
        <form method="POST" @submit.prevent="recaptcha" class="max-w-lg mx-r">
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
                <img v-if="message.image" :src="'../../storage/' + message.image" class="max-w-md" width="200">
                <input id="image" type="file" @input="imageUpload" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"/>
                <p v-if="errors.image" class="text-red-500 text-xs italic">{{ errors.image }}</p>
            </div>
            <div>
                <p v-if="errors.captcha" class="text-red-500 text-xs italic">{{ errors.captcha }}</p>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
        </form>
    </div>
</template>

<script setup>

import { useForm, Link, router } from '@inertiajs/vue3'
import { useReCaptcha } from "vue-recaptcha-v3";

const props = defineProps(['message', 'errors'])
const { executeRecaptcha, recaptchaLoaded } = useReCaptcha()

const form = useForm({
    name: props.message.name,
    email: props.message.email,
    message: props.message.message,
    image: null,
    captcha :null,
})

const imageUpload = (event) => {
    form.image = event.target.files[0]
    props.message.image = null
}

const recaptcha = async () => {
    await recaptchaLoaded()
    form.captcha = await executeRecaptcha('login')
    submit();
}

const submit = () => {
    router.post(`/guestbook/${props.message.id}`, {
        _method: 'put',
        name: form.name,
        email: form.email,
        message: form.message,
        image: form.image,
    })
}
</script>
