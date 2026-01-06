<script setup lang="ts">
    import { useRouter } from 'vue-router';
    import Field from '@/components/Input.vue';
    import { type AddPeopleHouseHoldData, type AddPeopleHouseHoldDataError,HouseHoldRoleEnum, PersonRoleEnum, type HouseHold, type IOption } from '@/models';
    import {  isBlank,resetError,GetCookie, isEmailValid } from "@/services";
    import { useHouseHold } from '@/composable/useHouseHold';
    import CustomSelect from '@/components/CustomSelect.vue';
    import { ref,onMounted,computed } from 'vue';

    const { loading, error, GetHouseHold, AddPeopleToHouseHold } = useHouseHold();

    interface IAddPeopleHouseHoldForm {
        PersonForm : AddPeopleHouseHoldData,
        PersonFormError : AddPeopleHouseHoldDataError                        
    }

    const houseHolds = ref<IOption[]>([])
    const PersonAdded = ref(false);

    onMounted(
        async () => {
           const res = await GetHouseHold(
                GetCookie('token')
            );
            if(res){
                const mapped: IOption[] = res.houseHolds.map((houseHold: HouseHold) => ({
                    label: houseHold.name,
                    value: houseHold.accessCode
                }))
                houseHolds.value = mapped;
            }
        }
    )

    const props = defineProps<IAddPeopleHouseHoldForm>()
    const router = useRouter();

    const SelectedAccessCode = computed(() => {
        const modelVal = props.PersonForm?.HouseHoldName?.value;
        if (!modelVal) return '';

        const byValue = houseHolds.value.find(h => h.value === modelVal);
        if (byValue) return byValue.value;

        const byLabel = houseHolds.value.find(h => h.label === modelVal);
        if (byLabel) return byLabel.value;

        return '';
    });


    const submitForm  = async () => { 
        error.value = '';
        resetError(props.PersonFormError);
        // on vérifie accessCode, role et le type d'utilisateur car ils sont obligatoires
        if( props.PersonForm.HouseHoldName.value === '' ){
            props.PersonFormError.HouseHoldName.value = 'Veuillez sélectionner un foyer';
            return;
        }
        if(props.PersonForm.userType.value === ''){
            props.PersonFormError.userType.value = 'Veuillez sélectionner un type d\'utilisateur';
            return;
        }
        if(props.PersonForm.role.value === ''){
            props.PersonFormError.role.value = 'Veuillez sélectionner un type de personne à ajouter';
            return;
        }
        // Ensuite on vérifie les autres champs selon le role
        if(props.PersonForm.userType.value === PersonRoleEnum.CHILD){
            if(props.PersonForm.name.value === ''){
                props.PersonFormError.name.value = 'Veuillez entrer un nom';
                return;
            }
            if(props.PersonForm.lastName.value === ''){
                props.PersonFormError.lastName.value = 'Veuillez entrer un prénom';
                return;
            }
            console.log(SelectedAccessCode.value)
            const PersonAdded = await AddPeopleToHouseHold(
                GetCookie('token'),
                SelectedAccessCode.value,
                props.PersonForm.role.value,
                props.PersonForm.name.value,
                props.PersonForm.lastName.value
            );
            if(PersonAdded){
                console.log(PersonAdded)
                PersonAdded.value = true;
            }
        }
        if(props.PersonForm.userType.value === PersonRoleEnum.ADULT){
            if(!isEmailValid(props.PersonForm.email.value)){
                props.PersonFormError.email.value = 'Veuillez entrer un email';
                return;
            }
            console.log(SelectedAccessCode.value, props.PersonForm.email.value,props.PersonForm.role.value)
            const PersonAdded =  await AddPeopleToHouseHold(
                GetCookie('token'),
                SelectedAccessCode.value,
                props.PersonForm.role.value,
                undefined,
                undefined,
                props.PersonForm.email.value,
            );
            if(PersonAdded){
                console.log(PersonAdded);
                PersonAdded.value = true;
            }
        }

    }

</script>

<template>
    <div class="p-4 bg-white shadow-lg rounded-lg lg:max-w-xl w-full mt-6">
        <div v-if="PersonAdded === true" class="flex flex-col gap-3">
            <h3 class="font-bold text-lg">La personne à été <span class="text-green_pastel">ajouté</span> !</h3>
            <button class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" @click="router.push('/dashboard')">Aller au tableau de bord</button>
        </div>
        <form v-else class="flex flex-col gap-2" @submit.prevent="submitForm">
            <CustomSelect title="Choisir le foyer" name="HouseHold" :placeholder="'Choissisez le foyer'" v-model="props.PersonForm.HouseHoldName.value" :options="houseHolds" :errorMessage="props.PersonFormError.HouseHoldName.value"/>
            <CustomSelect title="Type d'utilisateur" name="UserType" :placeholder="'Choissisez le type de personne'" v-model="props.PersonForm.userType.value" :options="PersonRoleEnum" :errorMessage="props.PersonFormError.userType.value"/>
            <CustomSelect title="Role" name="Role" :placeholder="'Choissisez le role de la personne'" v-model="props.PersonForm.role.value" :options="HouseHoldRoleEnum" :errorMessage="props.PersonFormError.role.value"/>
            <div v-if="props.PersonForm.userType.value === PersonRoleEnum.CHILD" class="mt-1 flex flex-col gap-2">
                <Field title="Name" name="Name" type="text" placeholder="Name" v-model="props.PersonForm.name.value" :errorMessage="props.PersonFormError.name.value"/>
                <Field title="Last Name" name="LastName" type="text" placeholder="Last Name" v-model="props.PersonForm.lastName.value" :errorMessage="props.PersonFormError.lastName.value"/>
            </div>
            <div v-if="props.PersonForm.userType.value === PersonRoleEnum.ADULT" class="mt-1">
                <Field title="Email" name="Email" type="email" placeholder="Email" v-model="props.PersonForm.email.value" :errorMessage="props.PersonFormError.email.value"/>
            </div>
            <button type="submit" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2" :disabled="loading">Ajouter la personne</button>
        </form>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
    </div>
</template>

