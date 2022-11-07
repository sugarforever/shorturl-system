<template>
    <Head title="Short URL System"></Head>
    <div>
        <form class="w-full" @submit.prevent="store">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
                    Create Link
                </label>
                <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" type="text" placeholder="Enter Long URL" v-model="form.long_url">
                <p class="text-gray-600 text-xs italic mb-2">Shorten the long URL for easier sharing</p>
                </div>
                <div class="w-full px-3">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Shorten
                    </button>
                </div>
            </div>
        </form>
        <div>
            <div class="border-b border-b-gray-500 font-bold py-4 px-2 text-2xl">Links</div>
            <ul class="py-6">
                <li v-for="(shortUrl) in shortUrls.data" :key="shortUrl.token">
                    <div class="border-b border-gray-200 p-2 mb-6">
                        <div class="flex flex-row">
                            <div class="font-normal text-xl flex-1 text-blue-700">
                                <a :href="shortUrl.short_url" target="_blank" class="hover:underline">{{ shortUrl.token }}</a>
                            </div>
                            <div class="flex justify-center items-center">
                                <img :src="`https://www.google.com/s2/favicons?sz=32&domain_url=${shortUrl.long_url}`" />
                            </div>
                        </div>
                        <div class="font-thin my-4 break-words">{{ shortUrl.long_url }}</div>
                        <div class="text-sm text-pink-500">
                            <div>
                                <i class="fa-solid fa-calendar-day"></i>
                                <span class="ml-2">{{ shortUrl.created_at }}</span>
                            </div>
                            <div class="count">
                                <i class="fa-solid fa-chart-simple"></i>
                                <span class="ml-2">{{ shortUrl.count }}</span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/ElementPlus/Layout.vue'
import { Inertia } from '@inertiajs/inertia'

export default {
    components: {
        Head,
    },
    props: {
        shortUrls: Object
    },
    data() {
        return {
            activeTab: 'myUrls',
            labelPosition: "top",
            form: this.$inertia.form({
                long_url: null
            })
        }
    },
    layout: Layout,
    methods: {
        store() {
            this.form.post('/shorturl', {
                onSuccess: () => this.form.reset('long_url'),
            })
        },
        generateQRCode(token) {
            Inertia.get('/shorturl/qrcode', {
                token: token
            })
        }
    }
}
</script>