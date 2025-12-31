<script setup lang="ts">
    import NavBarConnect from '@/components/Layout/NavBarConnect.vue';
    import JoinHouseHoldForm from '@/components/HouseHold/JoinHouseHoldForm.vue';
    import { type JoinHouseHoldData, type CreateHouseHoldData, type JoinHouseHoldDataError, type CreateHouseHoldDataError } from '@/models/'
    import { ref,watch, type Ref} from 'vue'
    import { useRouter,useRoute } from "vue-router";

    const props = defineProps({
        mode: {
            type: String,
            default: "CreateHouseHold",
        }
    });

    const router = useRouter();
    const route = useRoute();

    watch(
        () => route.query.mode,
        (newMode) => {
        FormType.value = newMode === "JoinHouseHold" ? "JoinHouseHold" : "CreateHouseHold";
        }
    );

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

    const FormType: Ref<"JoinHouseHold" | "CreateHouseHold"> = ref(props.mode === "JoinHouseHold" ? "JoinHouseHold" : "CreateHouseHold");

    const toggleDisplay = () => {
        FormType.value =
        FormType.value === "JoinHouseHold" ? "JoinHouseHold" : "CreateHouseHold";
        router.replace({ path: "/JoinHouseHold", query: { mode: FormType.value } });
    };

</script>

<template>
    <div class="p-4">
        <NavBarConnect/>
       <div class="mt-4">
          <JoinHouseHoldForm
            :HouseHold="JoinFormData"
            :HouseHoldError="JoinFormDataError"
            :toggleDisplay="toggleDisplay"
          />
       </div>
    </div>
</template>

