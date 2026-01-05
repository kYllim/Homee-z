<script setup lang="ts">
    import NavBarConnect from '@/components/Layout/NavBarConnect.vue';
    import { ref } from 'vue'
    import { useRouter,useRoute } from "vue-router";
    import AddPeopleHouseHoldForm from '@/components/HouseHold/AddPeopleHouseHoldForm.vue';
    import { type AddPeopleHouseHoldData, type AddPeopleHouseHoldDataError, HouseHoldRoleEnum} from '@/models';

    const HouseHoldPersonForm : AddPeopleHouseHoldData = {
        name: ref(''),
        email: ref(''),
        lastName: ref(''),
        role: ref(HouseHoldRoleEnum.MEMBER)
    };

    const HouseHoldPersonFormError : AddPeopleHouseHoldDataError = {
        name: ref(''),
        email: ref(''),
        lastName: ref(''),
        role: ref('')
    };

    const props = defineProps({});

    const router = useRouter();
    const route = useRoute();

</script>

<template>
    <div class="p-4">
        <NavBarConnect/>
       <div class="mt-4">
            <AddPeopleHouseHoldForm :PersonForm="HouseHoldPersonForm" :PersonFormError="HouseHoldPersonFormError" />
       </div>
    </div>
</template>

