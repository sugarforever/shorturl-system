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
            <div class="border-b border-b-pink-500 font-bold py-4 px-2 text-2xl">Links</div>
            <ul class="">
                <li v-for="(shortUrl) in shortUrls.data" :key="shortUrl.token">
                    <div class="border-b border-gray-200 px-2 py-4 hover:bg-pink-100">
                        <div class="flex flex-row">
                            <div class="flex justify-center items-center w-[54px] mr-2">
                                <img :src="`https://www.google.com/s2/favicons?sz=32&domain_url=${shortUrl.long_url}`" />
                            </div>
                            <div class="flex-1">
                                <div class="" v-html="shortUrl.title"></div>
                                <div class="flex mt-1">
                                    <span class="font-thin text-gray-600 text-sm">{{ shortUrl.created_at }}</span>
                                    <div class="ml-4 text-sm text-blue-600">
                                        <i class="fa-solid fa-chart-simple"></i>
                                        <span class="ml-2">{{ shortUrl.count }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div>
                                    <a :href="shortUrl.short_url" target="_blank" class="flex items-center justify-center px-2">{{ shortUrl.short_url }}</a>
                                </div>
                                <div class="flex justify-end mt-1">
                                    <a class="twitter-share-button"
                                        href="https://twitter.com/intent/tweet"
                                        data-size=""
                                        :data-text="shortUrl.title"
                                        :data-url="shortUrl.short_url"
                                        data-hashtags=""
                                        data-via="xl2ink"
                                        data-related="twitterapi,twitter">
                                        Tweet
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="mt-12 flex justify-center" v-if="pagination.totalPages > 1">
                <ul class="flex">
                    <li :class="`${pagination.firstPageUrl == '' ? 'border-gray-300' : 'border-blue-500'} w-[32px] h-[32px] border flex justify-center items-center mx-1 text-gray-700`">
                        <a class="w-full text-center" :href="pagination.firstPageUrl" v-if="pagination.firstPageUrl != ''"><i class="fa-solid fa-backward-step"></i></a>
                        <span v-if="pagination.firstPageUrl == ''"><i class="fa-solid fa-backward-step"></i></span>
                    </li>
                    <li :class="`${pagination.previousPageUrl == '' ? 'border-gray-300' : 'border-blue-500'} w-[32px] h-[32px] border flex justify-center items-center mx-1 text-gray-700`">
                        <a class="w-full text-center" :href="pagination.previousPageUrl" v-if="pagination.previousPageUrl != ''"><i class="fa-solid fa-chevron-left"></i></a>
                        <span v-if="pagination.previousPageUrl == ''"><i class="fa-solid fa-chevron-left"></i></span>
                    </li>
                    <li v-for="link in pagination.links" :key="link.index" 
                        :class="`${link.index == pagination.currentPage ? 'border-gray-300' : 'border-blue-500'} w-[32px] h-[32px] border flex justify-center items-center mx-1 text-gray-700`">
                        <a class="w-full text-center" :href="link.url" v-if="link.index != pagination.currentPage">{{ link.index }}</a>
                        <span v-if="link.index == pagination.currentPage">{{ link.index }}</span>
                    </li>
                    <li :class="`${pagination.nextPageUrl == '' ? 'border-gray-300' : 'border-blue-500'} w-[32px] h-[32px] border flex justify-center items-center mx-1 text-gray-700`">
                        <a class="w-full text-center" :href="pagination.nextPageUrl" v-if="pagination.nextPageUrl != ''"><i class="fa-solid fa-chevron-right"></i></a>
                        <span v-if="pagination.nextPageUrl == ''"><i class="fa-solid fa-chevron-right"></i></span>
                    </li>
                    <li :class="`${pagination.lastPageUrl == '' ? 'border-gray-300' : 'border-blue-500'} w-[32px] h-[32px] border flex justify-center items-center mx-1 text-gray-700`">
                        <a class="w-full text-center" :href="pagination.lastPageUrl" v-if="pagination.lastPageUrl != ''"><i class="fa-solid fa-forward-step"></i></a>
                        <span v-if="pagination.lastPageUrl == ''"><i class="fa-solid fa-forward-step"></i></span>
                    </li>
                </ul>
            </div>
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
        shortUrls: Object,
        pagination: Object
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