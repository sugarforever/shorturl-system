<template>

    <Head title="Short URL System"></Head>
    <el-row class="row-bg" :gutter="20">
        <el-col :lg="6" :md="24" :xs="24">
            <el-form :model="form" label-width="120px" :label-position="labelPosition" :size="`large`"
                @submit.prevent="store">
                <el-form-item label="CREATE LINK">
                    <el-input v-model="form.long_url" :rows="2" type="textarea" placeholder="ENTER LONG URL" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" native-type="submit">CREATE</el-button>
                </el-form-item>
            </el-form>
        </el-col>
        <el-col :lg="18" :md="24" :xs="24">
            <el-tabs v-model="activeTab">
                <el-tab-pane label="My URLs" name="myUrls">
                    <el-table :data="shortUrls.data" border style="width: 100%" :size="`large`">
                        <el-table-column prop="long_url" label="Long URL" />
                        <el-table-column label="Short URL">
                            <template #default="scope">
                                <el-link type="primary" :href="scope.row.short_url" target="_blank">
                                    {{ scope.row.short_url }}
                                </el-link>
                            </template>
                        </el-table-column>
                        <el-table-column prop="created_at" label="Created At" />
                        <el-table-column label="QR Code">
                            <template #default="scope" >
                                <el-button v-if="!scope.row.qr_code" type="primary" @click="generateQRCode(scope.row.token)">QR Code</el-button>
                                <img v-if="scope.row.qr_code" :src="scope.row.qr_code" />
                            </template>
                        </el-table-column>
                    </el-table>
                </el-tab-pane>
            </el-tabs>
        </el-col>
    </el-row>
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