<script setup lang="ts">
    import { ref } from 'vue';
    import { RouterLink } from 'vue-router'
    import type {FormConnexion,ErrorsConexion} from '../model/index'
    import Field from '@/components/Input.vue';

    interface Iconnexionform {
        data : FormConnexion,
        error : ErrorsConexion,
        toggleDisplay : () => void
    }

    const props = defineProps<Iconnexionform>()
</script>

<template>
    <div class="m-8 p-6 bg-stone-200 shadow-sm rounded-lg">
        <div class="flex flex-col items-center gap-1 mb-4">
            <span class="bg-brown_pastel px-5 py-4 flex items-center justify-center w-fit rounded-xl shadow-sm">
                <i class="pi pi-home text-green_pastel text-2xl"></i>
            </span>
            <h1 class="font-bold text-lg">Welcome Back</h1>
            <p class="text-sm">Sign in to manage your household chores</p>
        </div>
        <form class="flex flex-col gap-2">
            <Field title="Email Address" name="email" type="email" placeholder="Enter your email" v-model="props.data.emailConnexion.value" icon="pi-at" :errorMessage="props.error.emailConnexion.value"/>
            <Field title="Password" name="password" type="password" placeholder="Enter your password" v-model="props.data.passwordConnexion.value" icon="pi-lock" :errorMessage="props.error.passwordConnexion.value"/>
            <div class="flex flex-col justify-between my-2">
                <label class="flex gap-2">
                    <input type="checkbox" class="border-solid border-red-500"/>
                    <p class="font-semibold text-sm">Remember me</p>
                </label>
                <router-link to="/about" class="font-semibold text-sm text-green_pastel">Forgot Password ?</router-link>
            </div>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2">Sign in</button>
            <p class="text-sm text-center">Don't have an account ?</p>
            <p class="font-semibold text-md text-green_pastel text-center" @click="toggleDisplay">Create one now</p>
        </form>
    </div>
</template>

/* 
    a finir : 
    - faire les fonction de validation des champs et function de validation globale 
    - le reset des erreurs 
    - la soumission du form = liaison avec le back
    - le remember me
    - créer le composable pour les appel api 
    - les fonctions de cookie / token

*/
