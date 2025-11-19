<script setup lang="ts">
    import type {FormRegister,ErrorRegister} from '../model'
    import type { Ref } from 'vue'
    import Field from '@/components/Input.vue'

    interface IregisterForm {
        data : FormRegister,
        error : ErrorRegister,
        toggleDisplay : () => void
    }

    const props = defineProps<IregisterForm>()
</script>

<template>
    <div class="m-8 p-6 bg-stone-200 shadow-sm rounded-lg">
        <div class="flex flex-col items-center gap-1 mb-4">
            <span class="bg-brown_pastel px-5 py-4 flex items-center justify-center w-fit rounded-xl shadow-sm">
                <i class="pi pi-user-plus text-green_pastel text-2xl"></i>
            </span>
            <h1 class="font-bold text-lg">Create Account</h1>
            <p class="text-sm">Join us to organize your household tasks</p>
        </div>
        <form class="flex flex-col gap-2">
            <Field title="Email Address" name="email" type="email" placeholder="Enter your email" v-model="props.data.emailRegister.value" icon="pi-at" :errorMessage="props.error.emailRegister.value"/>
            <Field title="Password" name="password" type="password" placeholder="Enter your password" v-model="props.data.passwordRegister.value" icon="pi-lock" :errorMessage="props.error.passwordRegister.value"/>
            <Field title="Confirm Password" name="passwordConfirmation" type="password" placeholder="Confirm your password" v-model="props.data.passwordComfirmation.value" icon="pi-lock" :errorMessage="props.error.passwordComfirmation.value"/>
            <label class="flex gap-2">
                <input type="checkbox" class="border-solid border-red-500"/>
                <p class="font-semibold text-sm">I agree to the Terms of Service and Privacy Policy</p>
            </label>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2">Create Account</button>
            <p class="text-sm text-center">Already have an account ?</p>
            <p class="font-semibold text-md text-green_pastel text-center" @click="toggleDisplay">Sign in</p>
        </form>
    </div>
</template>


/* 
    a finir : 
    - faire les fonction de validation des champs et function de validation globale 
    - le reset des erreurs 
    - la soumission du form = liaison avec le back
    - le reste de la structure du form (first name, name)
    - créer le composable pour les appel api 
    - les fonctions de cookie / token

*/