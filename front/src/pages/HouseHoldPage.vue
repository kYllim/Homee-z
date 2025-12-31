<script setup lang="ts">
    import { ref,watch } from "vue";
    import type { Ref } from "vue";
    import { useRouter,useRoute } from "vue-router";
    import NavBarConnect from '@/components/Layout/NavBarConnect.vue';

    const props = defineProps({
        mode: {
        type: String,
        default: "connexion",
        }
    });

    const router = useRouter();
    const route = useRoute();

</script>

<template>
    <div class="p-4">
        <NavBarConnect/>
        <h1 class="font-bold text-2xl mb-2">Foyer</h1>
        <p class="font-regular text-sm">souhaitez-vous...</p>
        <div class="flex flex-col gap-2 p-4">
            <router-link :to="{ name : 'JoinHouseHold', query: { mode: 'CreateHouseHold'}}" class="px-3 py-2 bg-transparent border-solid border-2 border-green_pastel rounded-md text-green_pastel font-semibold lg:text-lg lg:px-4 hover:bg-green_pastel hover:text-white my-2 text-center">Rejoindre</router-link>
            <p>Ou</p>
            <router-link :to="{ name : 'JoinHouseHold', query: { mode: 'JoinHouseHold'}}" class="px-3 py-2 bg-green_pastel rounded-md text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2 text-center">Créer</router-link>
        </div>
    </div>
</template>