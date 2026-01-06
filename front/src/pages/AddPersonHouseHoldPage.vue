<script setup lang="ts">
    import NavBarConnect from '@/components/Layout/NavBarConnect.vue';
    import { ref } from 'vue'
    import { useRouter,useRoute } from "vue-router";
    import AddPeopleHouseHoldForm from '@/components/HouseHold/AddPeopleHouseHoldForm.vue';
    import { type AddPeopleHouseHoldData, type AddPeopleHouseHoldDataError, PersonRoleEnum} from '@/models';

    const HouseHoldPersonForm : AddPeopleHouseHoldData = {
        name: ref(''),
        email: ref(''),
        lastName: ref(''),
        role: ref(''),
        HouseHoldName : ref(''),
        userType : ref('')
    };

    const HouseHoldPersonFormError : AddPeopleHouseHoldDataError = {
        name: ref(''),
        email: ref(''),
        lastName: ref(''),
        role: ref(''),
        HouseHoldName : ref(''),
        userType : ref('')
    };

    const props = defineProps({});

    const router = useRouter();
    const route = useRoute();

</script>

<template>
    <div class="flex flex-col relative">
        <header class="w-full px-4 mb-6 relative z-50">
            <NavBarConnect />
        </header>

        <main class="flex-1 w-full flex  lg:justify-center px-4">
           <AddPeopleHouseHoldForm :PersonForm="HouseHoldPersonForm" :PersonFormError="HouseHoldPersonFormError" />
        </main>
    </div>
</template>

