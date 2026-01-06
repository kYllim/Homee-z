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
        await loginUser(props.dataConnexion.emailConnexion.value,props.dataConnexion.passwordConnexion.value);
        if (data.value && data.value.token) {
            setCookie('token', data.value.token, 7);
            router.push('/dashboard');
        }
    }
</script>

<template>
    <div class="m-8 p-6 bg-white shadow-lg rounded-lg lg:max-w-xl w-full">
        <div class="flex flex-col items-center gap-1 mb-4">
            <span class="bg-brown_pastel px-5 py-4 flex items-center justify-center w-fit rounded-xl shadow-sm">
                <i class="pi pi-home text-green_pastel text-2xl"></i>
            </span>
            <h1 class="font-bold text-lg">Welcome Back</h1>
            <p class="text-sm">Sign in to manage your household chores</p>
        </div>
        <form class="flex flex-col gap-2" @submit.prevent="submitForm">
            <Field title="Email Address" name="email" type="email" placeholder="Enter your email" v-model="props.dataConnexion.emailConnexion.value" icon="pi-at" :errorMessage="props.errorConnexion.emailConnexion.value"/>
            <Field title="Password" name="password" type="password" placeholder="Enter your password" v-model="props.dataConnexion.passwordConnexion.value" icon="pi-lock" :errorMessage="props.errorConnexion.passwordConnexion.value"/>
            <div class="flex flex-col justify-between my-2 gap-2 md:flex-row">
                <label class="flex gap-2">
                    <input type="checkbox" class="border-solid border-red-500"/>
                    <p class="font-semibold text-sm">Remember me</p>
                </label>
                <router-link to="/" class="font-semibold text-sm text-green_pastel">Forgot Password ?</router-link>
            </div>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" :disabled="loading">Sign in</button>
            <p class="text-sm text-center">Don't have an account ?</p>
            <p class="font-semibold text-md text-green_pastel text-center cursor-pointer" @click="toggleDisplay">Create one now</p>
        </form>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
    </div>
</template>

