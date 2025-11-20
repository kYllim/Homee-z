<script setup lang="ts">
    import type {FormRegister,ErrorRegister} from '../model'
    import {isEmailValid,isPasswordValid,PasswordMatching,isBlank,resetError} from '../services'
    import { ref } from 'vue'
    import Field from '@/components/Input.vue'
    import { registerUser } from '../services/auth'

    interface IregisterForm {
        data : FormRegister,
        error : ErrorRegister,
        toggleDisplay : () => void
    }
    
    const props = defineProps<IregisterForm>()

    const agreeToTerms = ref(false);
    let agreeError = ref("");


    const submitForm = async () => {
        resetError(props.error);
        agreeError.value = "";
        if(!isBlank(props.data,props.error)){
            return;
        }
        if(!isEmailValid(props.data.emailRegister.value)){
            props.error.emailRegister.value = "Invalid email address";
            return;
        }
        if(!isPasswordValid(props.data.passwordRegister.value)){
            props.error.passwordRegister.value = "Password must be at least 8 characters long and include a number and a special character";
            return;
        }
        if(!PasswordMatching(props.data.passwordRegister.value,props.data.passwordComfirmation.value)){
            props.error.passwordComfirmation.value = "Passwords do not match";
            return;
        }
        if(!agreeToTerms.value){
            agreeError.value = "You must agree to the terms of service and privacy policy";
            return;
        }
        try {
            const response = await registerUser(props.data.name.value,props.data.firstName.value,props.data.emailRegister.value,props.data.passwordRegister.value);
            console.log(response);
        } catch (error) {
            console.error("Registration failed:", error);
        }
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
            <Field title="First Name" name="firstName" type="text" placeholder="Enter your first name" v-model="props.data.firstName.value" :errorMessage="props.error.firstName.value"/>
            <Field title="Name" name="name" type="text" placeholder="Enter your name" v-model="props.data.name.value" :errorMessage="props.error.name.value"/>
            <Field title="Email Address" name="email" type="email" placeholder="Enter your email" v-model="props.data.emailRegister.value" icon="pi-at" :errorMessage="props.error.emailRegister.value"/>
            <Field title="Password" name="password" type="password" placeholder="Enter your password" v-model="props.data.passwordRegister.value" icon="pi-lock" :errorMessage="props.error.passwordRegister.value"/>
            <Field title="Confirm Password" name="passwordConfirmation" type="password" placeholder="Confirm your password" v-model="props.data.passwordComfirmation.value" icon="pi-lock" :errorMessage="props.error.passwordComfirmation.value"/>
            <label class="flex gap-2">
                <input type="checkbox" class="border-solid border-red-500"  v-model="agreeToTerms"/>
                <p class="font-semibold text-sm">I agree to the Terms of Service and Privacy Policy</p>
            </label>
            <span v-if="agreeError" class="text-red-500 text-sm mt-1">{{ agreeError }}</span>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2">Create Account</button>
            <p class="text-sm text-center">Already have an account ?</p>
            <p class="font-semibold text-md text-green_pastel text-center" @click="toggleDisplay">Sign in</p>
        </form>
    </div>
</template>
