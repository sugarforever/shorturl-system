<template>
    <div class="common-layout">
        <el-container>
            <el-header>
                <el-menu :default-active="activeIndex" class="el-menu-demo" mode="horizontal" :ellipsis="false"
                    @select="handleSelect">
                    <el-menu-item index="0">
                        SHORT URLs
                    </el-menu-item>
                    <div class="flex-grow" />
                    <el-menu-item index="1">Customer Care</el-menu-item>
                    <el-sub-menu index="2">
                        <template #title>My Account</template>
                        <el-menu-item index="2-1">Profile</el-menu-item>
                        <el-menu-item index="2-2">My URLs</el-menu-item>
                    </el-sub-menu>
                </el-menu>
            </el-header>
            <el-main>
                <slot />
            </el-main>
            <el-footer>
            </el-footer>
        </el-container>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

const activeIndex = ref('1')
const handleSelect = (key: string, keyPath: string[]) => {
    console.log(key, keyPath)
}
</script>

<style>
.flex-grow {
    flex-grow: 1;
}
</style>