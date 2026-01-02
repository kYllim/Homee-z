<script setup lang="ts">
    import { useRouter } from 'vue-router';
    import Field from '@/components/Input.vue';
    import { type JoinHouseHoldData, type JoinHouseHoldDataError } from '@/models';
    import {  isBlank,resetError,GetCookie } from "@/services";
    import { useHouseHold } from '@/composable/useHouseHold';
    import { ref } from 'vue';

    const { JoinHouseHold, loading, error, data } = useHouseHold();

    interface IjoinHouseHoldForm {
        toggleDisplay : () => void
        HouseHold : JoinHouseHoldData,
        HouseHoldError : JoinHouseHoldDataError
    }

    const props = defineProps<IjoinHouseHoldForm>()
    const router = useRouter();
    const houseHold = ref<{message: string, name : string} | null>(null);

    const submitForm  = async () => { 
        error.value = '';
        resetError(props.HouseHoldError);
        if(!isBlank(props.HouseHold,props.HouseHoldError)){
            return;
        }
        await JoinHouseHold(
            props.HouseHold.CodeHouseHold.value,
            GetCookie('token')
        );
        if(data.value){
            houseHold.value = data.value;
            console.log(data.value.name);
        }

    }


</script>

<template>
    <div class="p-4 bg-white shadow-lg rounded-lg lg:max-w-xl w-full mt-6">
        <!-- Si c'est validé -->
        <div v-if="houseHold" class="flex flex-col gap-3">
            <h3 class="font-bold text-lg">Votre code a été <span class="text-green_pastel">validé</span></h3>
            <p class="font-regular">Vous appartenez désormais à la famille :</p>
            <h4 class="flex self-center font-bold text-lg">{{ houseHold.name }}</h4>
            <button class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" @click="router.push('/dashboard')">Aller au tableau de bord</button>
        </div>
        <form v-else class="flex flex-col gap-2" @submit.prevent="submitForm">
            <Field title="Saisir le code du foyer" name="CodeHouseHold" type="text" placeholder="Code du foyer" v-model="props.HouseHold.CodeHouseHold.value" :errorMessage="props.HouseHoldError.CodeHouseHoldError.value"/>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" :disabled="loading">Rejoindre</button>
            <p class="text-sm text-center">Vous n'appartenez pas à un foyer ?</p>
            <p class="font-semibold text-sm text-green_pastel text-center cursor-pointer" @click="toggleDisplay">Créer un foyer</p>
        </form>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
    </div>
</template>

