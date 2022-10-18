<template>

    <Head title="Short URL System"></Head>
    <el-row class="row-bg" justify="space-evenly">
        <el-col :lg="8" :md="6" :xs="0">
        </el-col>
        <el-col :lg="8" :md="12" :xs="24">
            <el-form :model="form" label-width="120px" :label-position="labelPosition">
                <el-form-item label="Long URL to shorten">
                    <el-input v-model="form.url" />
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit">Shorten</el-button>
                </el-form-item>
            </el-form>
        </el-col>
        <el-col :lg="8" :md="6" :xs="0">
        </el-col>
    </el-row>

</template>

<script>
import { Head } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/ElementPlus/Layout.vue'
import { reactive } from 'vue'

export default {
    components: {
        Head,
    },
    props: {
        message: String
    },
    data() {
        return {
            labelPosition: "top"
        }
    },
    layout: Layout,
}
</script>

<script setup>
const form = reactive({
    url: ''
})

const onSubmit = () => {
    console.log('submit!')
}
</script>