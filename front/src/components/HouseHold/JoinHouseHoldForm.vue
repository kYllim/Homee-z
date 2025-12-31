<script setup lang="ts">
    import { useRouter } from 'vue-router'
    import Field from '@/components/Input.vue'
    import { type JoinHouseHoldData, type JoinHouseHoldDataError } from '@/models'
    import {  isBlank,resetError,GetCookie } from "@/services"
    import { useHouseHold } from '@/composable/useHouseHold'

    const { CreateHouseHold, loading, error, data } = useHouseHold();

    interface IjoinHouseHoldForm {
        toggleDisplay : () => void
        HouseHold : JoinHouseHoldData,
        HouseHoldError : JoinHouseHoldDataError
    }

    const props = defineProps<IjoinHouseHoldForm>()

    const submitForm  = async () => { 
        error.value = '';
        resetError(props.HouseHoldError);
        if(!isBlank(props.HouseHold,props.HouseHoldError)){
            return;
        }
        await CreateHouseHold(
            props.HouseHold.CodeHouseHold.value,
            GetCookie('token') as string
        );
    }


</script>

<template>
    <div class="p-4 bg-white shadow-lg rounded-lg lg:max-w-xl w-full">
        <h1 class="font-bold text-2xl mb-2">Rejoindre un foyer</h1>
        <form class="flex flex-col gap-2" @submit.prevent="submitForm">
            <Field title="Saisir le code du foyer" name="CodeHouseHold" type="text" placeholder="Code du foyer" v-model="props.HouseHold.CodeHouseHold.value" :errorMessage="props.HouseHoldError.CodeHouseHoldError.value"/>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" :disabled="loading">Rejoindre</button>
            <p class="text-sm text-center">Vous n'appartenez pas à un foyer ?</p>
            <p class="font-semibold text-sm text-green_pastel text-center cursor-pointer" @click="toggleDisplay">Créer un foyer</p>
        </form>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
    </div>
</template>

