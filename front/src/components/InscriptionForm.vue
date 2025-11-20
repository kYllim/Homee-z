<script setup lang="ts">
    import { ref } from 'vue'
    import { useRouter } from 'vue-router'
    import type {FormRegister,ErrorRegister} from '../model'
    import {isEmailValid,isPasswordValid,PasswordMatching,isBlank,resetError,setCookie} from '../services'
    import { useAuth } from '@/composable/useAuth'
    import Field from '@/components/Input.vue'

    const { registerUser, loading, error, data } = useAuth();

    interface IregisterForm {
        dataRegister : FormRegister,
        errorRegister : ErrorRegister,
        toggleDisplay : () => void
    }
    
    const props = defineProps<IregisterForm>()

    const router = useRouter();
    const agreeToTerms = ref(false);
    let agreeError = ref("");

    const submitForm = async () => {
        error.value = '';
        resetError(props.errorRegister);
        agreeError.value = "";
        if(!isBlank(props.dataRegister,props.errorRegister)){
            return;
        }
        if(!isEmailValid(props.dataRegister.emailRegister.value)){
            props.errorRegister.emailRegister.value = "Invalid email address";
            return;
        }
        if(!isPasswordValid(props.dataRegister.passwordRegister.value)){
            props.errorRegister.passwordRegister.value = "Password must be at least 8 characters long and include a number and a special character";
            return;
        }
        if(!PasswordMatching(props.dataRegister.passwordRegister.value,props.dataRegister.passwordComfirmation.value)){
            props.errorRegister.passwordComfirmation.value = "Passwords do not match";
            return;
        }
        if(!agreeToTerms.value){
            agreeError.value = "You must agree to the terms of service and privacy policy";
            return;
        }
        await registerUser(props.dataRegister.name.value,props.dataRegister.firstName.value,props.dataRegister.emailRegister.value,props.dataRegister.passwordRegister.value);
        setCookie('token', data.value.token,7);
        router.push('/dashboard');
    }
</script>

<template>
    <div class="m-8 p-6 bg-stone-200 shadow-sm rounded-lg">
        <div class="flex flex-col items-center gap-1 mb-4">
            <span class="bg-brown_pastel px-5 py-4 flex items-center justify-center w-fit rounded-xl shadow-sm">
                <i class="pi pi-user-plus text-green_pastel text-2xl"></i>
            </span>
            <h1 class="font-bold text-lg">Create Account</h1>
            <p class="text-sm">Join us to organize your hou sehold tasks</p>
        </div>
        <form class="flex flex-col gap-2" @submit.prevent="submitForm">
            <Field title="First Name" name="firstName" type="text" placeholder="Enter your first name" v-model="props.dataRegister.firstName.value" :errorMessage="props.errorRegister.firstName.value"/>
            <Field title="Name" name="name" type="text" placeholder="Enter your name" v-model="props.dataRegister.name.value" :errorMessage="props.errorRegister.name.value"/>
            <Field title="Email Address" name="email" type="email" placeholder="Enter your email" v-model="props.dataRegister.emailRegister.value" icon="pi-at" :errorMessage="props.errorRegister.emailRegister.value"/>
            <Field title="Password" name="password" type="password" placeholder="Enter your password" v-model="props.dataRegister.passwordRegister.value" icon="pi-lock" :errorMessage="props.errorRegister.passwordRegister.value"/>
            <Field title="Confirm Password" name="passwordConfirmation" type="password" placeholder="Confirm your password" v-model="props.dataRegister.passwordComfirmation.value" icon="pi-lock" :errorMessage="props.errorRegister.passwordComfirmation.value"/>
            <label class="flex gap-2">
                <input type="checkbox" class="border-solid border-red-500"  v-model="agreeToTerms"/>
                <p class="font-semibold text-sm">I agree to the Terms of Service and Privacy Policy</p>
            </label>
            <span v-if="agreeError" class="text-red-500 text-sm mt-1">{{ agreeError }}</span>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" :disabled="loading">Create Account</button>
            <p class="text-sm text-center">Already have an account ?</p>
            <p class="font-semibold text-md text-green_pastel text-center" @click="toggleDisplay">Sign in</p>
        </form>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
    </div>
</template>
