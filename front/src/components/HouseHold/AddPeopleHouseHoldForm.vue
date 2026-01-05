<script setup lang="ts">
    import { useRouter } from 'vue-router';
    import Field from '@/components/Input.vue';
    import { type AddPeopleHouseHoldData, type AddPeopleHouseHoldDataError } from '@/models';
    import {  isBlank,resetError,GetCookie } from "@/services";
    import { useHouseHold } from '@/composable/useHouseHold';
    import { HouseHoldRoleEnum } from '@/models/';
    import CustomSelect from '@/components/CustomSelect.vue';

    const { loading, error, data } = useHouseHold();

    interface IAddPeopleHouseHoldForm {
        PersonForm : AddPeopleHouseHoldData,
        PersonFormError : AddPeopleHouseHoldDataError
    }

    const props = defineProps<IAddPeopleHouseHoldForm>()
    const router = useRouter();

    const submitForm  = async () => { 
        error.value = '';
        resetError(props.PersonFormError);
        if(!isBlank(props.PersonForm,props.PersonFormError)){
            return;
        }

    }

</script>

<template>
    <div class="p-4 bg-white shadow-lg rounded-lg lg:max-w-xl w-full mt-6">
        <form class="flex flex-col gap-2" @submit.prevent="submitForm">
            <CustomSelect title="Role" name="Role" v-model="props.PersonForm.role.value" :options="HouseHoldRoleEnum" :errorMessage="props.PersonFormError.role.value"/>
            <div>
                <Field title="Name" name="Name" type="text" placeholder="Name" v-model="props.PersonForm.name.value" :errorMessage="props.PersonFormError.name.value"/>
                <Field title="Last Name" name="LastName" type="text" placeholder="Last Name" v-model="props.PersonForm.lastName.value" :errorMessage="props.PersonFormError.lastName.value"/>
            </div>
            <div>
                <Field title="Email" name="Email" type="email" placeholder="Email" v-model="props.PersonForm.email.value" :errorMessage="props.PersonFormError.email.value"/>
            </div>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" :disabled="loading">Ajouter la personne</button>
        </form>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
    </div>
</template>

