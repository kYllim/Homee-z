<script setup lang="ts">
    import { useRouter } from 'vue-router';
    import {HouseHoldRoleEnum, PersonRoleEnum, type HouseHold, type IOption } from '@/models';
    import { GetCookie } from "@/services";
    import { useHouseHold } from '@/composable/useHouseHold';
    import CustomSelect from '@/components/CustomSelect.vue';
    import { ref,onMounted,computed, type Ref, watch } from 'vue';

    const { loading, error, GetHouseHold, GetHouseHoldMembers, CheckIsAdmin, DeletePeopleHouseHold } = useHouseHold();

    interface IFamillyList {
        HouseHold : Ref<string>,
        HouseHoldError : Ref<string>
    }

    interface IMembers { 
        id : number,
        firstName : string,
        lastName : string,
        role : HouseHoldRoleEnum,
        email : string | null
    }

    const HouseHoldSelected : IFamillyList = ({
        HouseHold : ref(''),
        HouseHoldError : ref('')
    })

    const houseHolds = ref<IOption[]>([])
    const DataBase = ref<HouseHold[]>([])
    const houseHoldMembers = ref<IMembers[]>([])
    const isAdmin = ref<boolean | null>(null)
    const deletingMemberId = ref<number | null>(null)

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
                DataBase.value = res.houseHolds
                if(mapped.length > 0){
                    HouseHoldSelected.HouseHold.value = mapped[0]?.value || '';
                    await loadMembersForSelected();
                    const IsAdminInHouseHold = await CheckIsAdmin(GetCookie('token'), mapped[0]?.value || '');
                    if (IsAdminInHouseHold) {
                        isAdmin.value = !!(IsAdminInHouseHold.isAdmin ?? IsAdminInHouseHold === true)
                    }
                }
            }
        }
    )

    const SelectedAccessCode = computed(() => {
        const model = HouseHoldSelected.HouseHold.value;
        if (!model) return '';
        const byValue = houseHolds.value.find((h: IOption) => h.value === model);
        if (byValue) return byValue.value;
        const byLabel = houseHolds.value.find((h: IOption) => h.label === model);
        return byLabel?.value || '';
    });

    async function loadMembersForSelected() {
        const accessCode = SelectedAccessCode.value;
        if (!accessCode) {
            houseHoldMembers.value = [];
            return;
        }
        try {
            const res = await GetHouseHoldMembers(GetCookie('token'), accessCode);
            if (!res) {
                houseHoldMembers.value = [];
            } else if (Array.isArray(res)) {
                houseHoldMembers.value = res as IMembers[];
            } else if (Array.isArray((res as any).members)) {
                houseHoldMembers.value = (res as any).members as IMembers[];
            } else if (Array.isArray((res as any).data)) {
                houseHoldMembers.value = (res as any).data as IMembers[];
            } else {
                houseHoldMembers.value = [];
            }

            // also refresh admin state for this access code
            const IsAdminInHouseHold = await CheckIsAdmin(GetCookie('token'), accessCode);
            if (IsAdminInHouseHold) {
                isAdmin.value = !!(IsAdminInHouseHold.isAdmin ?? IsAdminInHouseHold === true)
            }
        } catch (e) {
            console.error('Failed to load members', e)
            houseHoldMembers.value = [];
        }
    }
    
    async function DeletePeople(id: number) {
        if (!id) return;
        try {
            deletingMemberId.value = id;
            await DeletePeopleHouseHold(GetCookie('token'), SelectedAccessCode.value, id);
            await loadMembersForSelected();
        } catch (e) {
            console.error('Failed to delete member', e);
            alert('Impossible de supprimer le membre.');
        } finally {
            deletingMemberId.value = null;
        }
    }

    watch(SelectedAccessCode, (newVal, oldVal) => {
        console.log('[FamillyList] SelectedAccessCode changed', { oldVal, newVal });
        loadMembersForSelected();
    });

</script>

<template>
    <div class="p-4 bg-white shadow-lg rounded-lg lg:max-w-xl w-full mt-6">
    <div v-if="houseHolds.length === 0">
            <p class="font-regular text-sm">Vous n'appartenez à aucun foyer, souhaitez-vous :</p>
            <div class="flex flex-col gap-2 p-4">
                <router-link :to="{ name : 'JoinHouseHold', query: { mode: 'JoinHouseHold'}}" class="px-3 py-2 bg-transparent border-solid border-2 border-green_pastel rounded-md text-green_pastel font-semibold lg:text-lg lg:px-4 hover:bg-green_pastel hover:text-white my-2 text-center">Rejoindre</router-link>
                <p>Ou</p>
                <router-link :to="{ name : 'JoinHouseHold', query: { mode: 'CreateHouseHold'}}" class="px-3 py-2 bg-green_pastel rounded-md text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80 my-2 text-center">Créer</router-link>
            </div>
        </div>
        <div v-else>
            <h1 class="font-bold text-2xl mb-4 text-center">Famille</h1>
            <CustomSelect title="Choisir le foyer" name="HouseHold" :placeholder="'Choissisez le foyer'" v-model="HouseHoldSelected.HouseHold.value" :options="houseHolds" :errorMessage="HouseHoldSelected.HouseHoldError.value" :aria-disabled="loading"/>
        </div>
        <div v-if="houseHoldMembers.length > 0" class="mt-4">
            <h2 class="text-lg font-semibold mb-3">Membres du foyer</h2>
            <ul class="flex flex-col gap-3">
                <li v-for="member in houseHoldMembers" :key="member.id" class="flex items-center justify-between bg-white border border-gray-100 rounded-lg p-3 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-semibold text-gray-700">
                            {{ (member.firstName && member.lastName) ? (member.firstName.charAt(0) + member.lastName.charAt(0)) : 'U' }}
                        </div>
                        <div>
                            <div class="font-medium text-sm">{{ member.firstName }} {{ member.lastName }}</div>
                            <div class="text-xs text-gray-500">{{ member.email ?? '' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs px-2 py-1 bg-gray-100 rounded-full text-gray-700">{{ member.role }}</span>
                        <button
                            v-if="isAdmin === true"
                            :disabled="deletingMemberId === member.id"
                            @click="DeletePeople(member.id)"
                            class="px-3 py-1 bg-red-500 text-white rounded-md text-sm hover:opacity-90 disabled:opacity-50"
                        >
                            <span v-if="deletingMemberId === member.id" class="pi pi-spin pi-spinner mr-2"></span>
                            Supprimer
                        </button>
                    </div>
                </li>
            </ul>
        </div>
        <i v-if="loading" class="pi pi-spin pi-spinner text-center" style="font-size: 2rem"></i>
        <span v-if="error" class="text-red-500 text-sm mt-1 flex items-center justify-center text-center">{{ error }}</span>
    </div>
</template>

