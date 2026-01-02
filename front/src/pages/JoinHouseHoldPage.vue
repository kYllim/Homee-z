<script setup lang="ts">
    import NavBarConnect from '@/components/Layout/NavBarConnect.vue';
    import JoinHouseHoldForm from '@/components/HouseHold/JoinHouseHoldForm.vue';
    import { type JoinHouseHoldData, type CreateHouseHoldData, type JoinHouseHoldDataError, type CreateHouseHoldDataError } from '@/models/'
    import { ref,computed, type Ref} from 'vue'
    import { useRouter,useRoute } from "vue-router";

    const props = defineProps({
        mode: {
            type: String,
            default: "CreateHouseHold",
        }
    });

    const router = useRouter();
    const route = useRoute();

    const JoinFormData : JoinHouseHoldData = {
        CodeHouseHold: ref(''),
    };

    const JoinFormDataError : JoinHouseHoldDataError = {
        CodeHouseHoldError: ref(''),
    };

    const CreateFormData : CreateHouseHoldData = {
        NameHouseHold: ref(''),
    };

    const CreateFormDataError : CreateHouseHoldDataError = {
        NameHouseHoldError: ref(''),
    };

    const FormType = computed<"JoinHouseHold" | "CreateHouseHold">(() => {
        return route.query.mode === "JoinHouseHold"
        ? "JoinHouseHold"
        : "CreateHouseHold";
    });


    const toggleDisplay = () => {
        FormType.value === "JoinHouseHold" ? "JoinHouseHold" : "CreateHouseHold";
        router.replace({ path: "/JoinHouseHold", query: { mode: FormType.value } });
    };  

</script>

<template>
    <div class="p-4">
        <NavBarConnect/>
       <div class="mt-4">
            <div v-if="FormType === 'JoinHouseHold'">
                <h1 class="font-bold text-2xl mb-2">Rejoindre un foyer</h1>
                <JoinHouseHoldForm
                    :HouseHold="JoinFormData"
                    :HouseHoldError="JoinFormDataError"
                    :toggleDisplay="toggleDisplay"
                />
            </div>
            <div v-else>
                <h1 class="font-bold text-2xl mb-2">Créer un foyer</h1>
            </div>
       </div>
    </div>
</template>

