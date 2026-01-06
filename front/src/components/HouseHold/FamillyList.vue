<script setup lang="ts">
    import { useRouter } from 'vue-router';
    import {HouseHoldRoleEnum, PersonRoleEnum, type HouseHold, type IOption } from '@/models';
    import {  isBlank,resetError,GetCookie, isEmailValid } from "@/services";
    import { useHouseHold } from '@/composable/useHouseHold';
    import CustomSelect from '@/components/CustomSelect.vue';
    import { ref,onMounted,computed, type Ref } from 'vue';

    const { loading, error, GetHouseHold } = useHouseHold();

    interface IFamillyList {
        HouseHold : Ref<string>,
        HouseHoldError : Ref<string>
    }

    const HouseHoldSelected : IFamillyList = ({
        HouseHold : ref(''),
        HouseHoldError : ref('')
    })

    const houseHolds = ref<IOption[]>([])

    onMounted(
        async () => {
            const res = await GetHouseHold(
                GetCookie('token')
            );
            if(res){
                console.log(res)
                const mapped: IOption[] = res.houseHolds.map((houseHold: HouseHold) => ({
                    label: houseHold.name,
                    value: houseHold.accessCode
                }))
                houseHolds.value = mapped;
                if(mapped.length > 0){
                    HouseHoldSelected.HouseHold.value = mapped[0]?.label ?? '';
                }
            }
        }
    )

    const router = useRouter();


</script>

<template>
    <div class="p-4 bg-white shadow-lg rounded-lg lg:max-w-xl w-full mt-6">
        <CustomSelect title="Choisir le foyer" name="HouseHold" :placeholder="'Choissisez le foyer'" v-model="HouseHoldSelected.HouseHold.value" :options="houseHolds" :errorMessage="HouseHoldSelected.HouseHoldError.value"/>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
    </div>
</template>

