<script setup lang="ts">
    import { useRouter } from 'vue-router'
    import type {FormConnexion,ErrorsConexion} from '@/models/'
    import {isEmailValid,isPasswordValid,isBlank,resetError,setCookie} from '@/services'
    import { useAuth } from '@/composable/useAuth'
    import Field from '@/components/Input.vue'

    interface Iconnexionform {
        dataConnexion : FormConnexion,
        errorConnexion : ErrorsConexion,
        toggleDisplay : () => void
    }

    const props = defineProps<Iconnexionform>()
    const { loginUser, loading, error, data } = useAuth();
    const router = useRouter();

    const submitForm = async () => {
        error.value = '';
        resetError(props.errorConnexion);
        if(!isBlank(props.dataConnexion,props.errorConnexion)){
            return;
        }
        if(!isEmailValid(props.dataConnexion.emailConnexion.value)){
            props.errorConnexion.emailConnexion.value = "Invalid email address";
            return;
        }
        if(!isPasswordValid(props.dataConnexion.passwordConnexion.value)){
            props.errorConnexion.passwordConnexion.value = "Password must be at least 8 characters long and include a number and a special character";
            return;
        }
        const token = await loginUser(props.dataConnexion.emailConnexion.value,props.dataConnexion.passwordConnexion.value);
        setCookie('token', token.token,7);
        router.push('/dashboard');
    }
</script>

<template>
    <div class="m-8 p-6 bg-white shadow-lg rounded-lg lg:max-w-xl w-full">
        <div class="flex flex-col items-center gap-1 mb-4">
            <span class="bg-brown_pastel px-5 py-4 flex items-center justify-center w-fit rounded-xl shadow-sm">
                <i class="pi pi-home text-green_pastel text-2xl"></i>
            </span>
            <h1 class="font-bold text-lg">Vous revoilà !</h1>
            <p class="text-sm">Connectez-vous pour gérer vos tâches ménagères</p>
        </div>
        <form class="flex flex-col gap-2" @submit.prevent="submitForm">
            <Field title="Adresse e-mail" name="email" type="email" placeholder="Entrez votre e-mail" v-model="props.dataConnexion.emailConnexion.value" icon="pi-at" :errorMessage="props.errorConnexion.emailConnexion.value"/>
            <Field title="Mot de passe" name="password" type="password" placeholder="Entrez votre mot de passe" v-model="props.dataConnexion.passwordConnexion.value" icon="pi-lock" :errorMessage="props.errorConnexion.passwordConnexion.value"/>
            <div class="flex flex-col justify-between my-2 gap-2 md:flex-row">
                <label class="flex gap-2">
                    <input type="checkbox" class="border-solid border-red-500"/>
                    <p class="font-semibold text-sm">Se souvenir de moi</p>
                </label>
                <router-link to="/" class="font-semibold text-sm text-green_pastel">Mot de passe oublié ?</router-link>
            </div>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" :disabled="loading">Se connecter</button>
            <p class="text-sm text-center">Vous n'avez pas de compte ?</p>
            <p class="font-semibold text-md text-green_pastel text-center cursor-pointer" @click="toggleDisplay">Créez-en un maintenant</p>
        </form>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
    </div>
</template>

