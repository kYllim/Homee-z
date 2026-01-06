<script setup lang="ts">
    import { useRouter } from 'vue-router';
    import Field from '@/components/Input.vue';
    import { type CreateHouseHoldData, type CreateHouseHoldDataError } from '@/models';
    import {  isBlank,resetError,GetCookie } from "@/services";
    import { useHouseHold } from '@/composable/useHouseHold';
    import { ref } from 'vue';

    const { CreateHouseHold, loading, error, data } = useHouseHold();

    interface ICreateHouseHoldForm {
        toggleDisplay : () => void
        HouseHold : CreateHouseHoldData,
        HouseHoldError : CreateHouseHoldDataError
    }

    const props = defineProps<ICreateHouseHoldForm>()
    const router = useRouter();
    const houseHold = ref<{accessCode : string} | null>(null);

    const submitForm  = async () => { 
        error.value = '';
        resetError(props.HouseHoldError);
        if(!isBlank(props.HouseHold,props.HouseHoldError)){
            return;
        }
        console.log('Validation passed', props.HouseHold.NameHouseHold.value);
        await CreateHouseHold(
            props.HouseHold.NameHouseHold.value,
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
        <h1 class="font-bold text-2xl mb-4 text-center">Créer un foyer</h1>
        <div v-if="houseHold" class="flex flex-col gap-3">
            <h3 class="font-bold text-lg">Votre foyer a été <span class="text-green_pastel">crée</span> !</h3>
            <p class="font-regular">Partager votre code d'accés :</p>
            <h4 class="flex self-center font-bold text-lg">{{ houseHold.accessCode }}</h4>
            <button class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" @click="router.push('/dashboard')">Aller au tableau de bord</button>
        </div>
        <form v-else class="flex flex-col gap-2" @submit.prevent="submitForm">
            <Field title="Saisir le nom du foyer" name="nameHouseHold" type="text" placeholder="Nom du foyer" v-model="props.HouseHold.NameHouseHold.value" :errorMessage="props.HouseHoldError.NameHouseHold.value"/>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" :disabled="loading">Créer</button>
            <p class="text-sm text-center">Vous souhaitez rejoindre un foyer ?</p>
            <p class="font-semibold text-sm text-green_pastel text-center cursor-pointer" @click="toggleDisplay">Rejoindre un foyer</p>
        </form>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
    </div>
</template>

