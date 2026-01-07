<script setup lang="ts">
    import NavBarConnect from '@/components/Layout/NavBarConnect.vue';
    import JoinHouseHoldForm from '@/components/HouseHold/JoinHouseHoldForm.vue';
    import CreateHouseHoldForm from '@/components/HouseHold/CreateHouseHoldForm.vue';
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
        CodeHouseHold: ref(''),
    };

    const CreateFormData : CreateHouseHoldData = {
        NameHouseHold: ref(''),
    };

    const CreateFormDataError : CreateHouseHoldDataError = {
        NameHouseHold: ref(''),
    };

    const FormType = computed<"JoinHouseHold" | "CreateHouseHold">(() => {
        return route.query.mode === "JoinHouseHold"
        ? "JoinHouseHold"
        : "CreateHouseHold";
    });


    const toggleDisplay = () => {
        if(FormType.value === "CreateHouseHold") {
            router.replace({ path: "/JoinHouseHold", query: { mode: "JoinHouseHold" } });
        } else {
            router.replace({ path: "/JoinHouseHold", query: { mode: "CreateHouseHold" } });
        }
    };  

</script>

<template>
    <div class="flex flex-col relative">
        <header class="w-full px-4 mb-6 relative z-50">
            <NavBarConnect />
        </header>

        <main class="flex-1 w-full flex  lg:justify-center px-4">
            <div class="w-full max-w-md sm:max-w-lg md:max-w-2xl">
                <div v-if="FormType === 'JoinHouseHold'" class="px-2">
                    <JoinHouseHoldForm
                        :HouseHold="JoinFormData"
                        :HouseHoldError="JoinFormDataError"
                        :toggleDisplay="toggleDisplay"
                    />
                </div>
                <div v-else class="px-2">
                    <CreateHouseHoldForm
                        :HouseHold="CreateFormData"
                        :HouseHoldError="CreateFormDataError"
                        :toggleDisplay="toggleDisplay"
                    />
                </div>
            </div>
        </main>
    </div>
</template>

