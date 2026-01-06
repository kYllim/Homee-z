<script setup lang="ts">
    import NavBarConnect from '@/components/Layout/NavBarConnect.vue';
    import FamillyList from '@/components/HouseHold/FamillyList.vue';
    import { useRouter,useRoute } from "vue-router";

    const router = useRouter();
    const route = useRoute();

</script>

<template>
    <div class="flex flex-col relative">
        <header class="w-full px-4 mb-6 relative z-50">
            <NavBarConnect />
        </header>

        <main class="flex-1 w-full flex  lg:justify-center px-4">
            <div class="w-full max-w-md sm:max-w-lg md:max-w-2xl">
                <FamillyList/>
            </div>
        </main>
        
    </div>
</template>

