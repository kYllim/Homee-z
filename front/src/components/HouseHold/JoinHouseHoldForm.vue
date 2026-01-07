<script setup lang="ts">
    import { useRouter } from 'vue-router';
    import Field from '@/components/Input.vue';
    import { type JoinHouseHoldData, type JoinHouseHoldDataError } from '@/models';
    import { isBlank, resetError, GetCookie } from "@/services";
    import { useHouseHold } from '@/composable/useHouseHold';
    import { ref } from 'vue';

    const { JoinHouseHold, loading, error } = useHouseHold();

    interface IjoinHouseHoldForm {
        toggleDisplay: () => void
        HouseHold: JoinHouseHoldData,
        HouseHoldError: JoinHouseHoldDataError
    }

    const props = defineProps<IjoinHouseHoldForm>()
    const router = useRouter();
    const houseHold = ref<{ message: string, name: string } | null>(null);

    const submitForm = async () => {
        error.value = '';
        resetError(props.HouseHoldError);
        if (!isBlank(props.HouseHold, props.HouseHoldError)) {
            return;
        }
        const HouseHoldJoined = await JoinHouseHold(
            props.HouseHold.CodeHouseHold.value,
            GetCookie('token')
        );
        if (HouseHoldJoined) {
            houseHold.value = HouseHoldJoined;
        }

    }
</script>
<template>
    <div class="p-4 bg-white shadow-lg rounded-lg lg:max-w-xl w-full mt-6">
        <h1 class="font-bold text-2xl mb-4 text-center">Rejoindre un foyer</h1>
        <!-- Si c'est validé -->
        <div v-if="houseHold" class="flex flex-col gap-3 text-center">
            <h3 class="font-bold text-lg">
                Votre code a été <span class="text-green_pastel">validé</span>
            </h3>
            <p class="font-regular">
                Vous appartenez désormais à la famille :
            </p>
            <h4 class="font-bold text-lg">{{ houseHold.name }}</h4>
            <button class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold hover:opacity-80 my-2"
                @click="router.push('/dashboard')">
                Aller au tableau de bord
            </button>
        </div>

        <!-- Formulaire -->
        <form v-else class="flex flex-col gap-3" @submit.prevent="submitForm">
            <Field title="Saisir le code du foyer" name="CodeHouseHold" type="text" placeholder="Code du foyer"
                v-model="props.HouseHold.CodeHouseHold.value"
                :errorMessage="props.HouseHoldError.CodeHouseHold.value" />

                            <button type="submit"
                                class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold hover:opacity-80 my-2 w-full"
                                :disabled="loading">
                Rejoindre
            </button>

            <p class="text-sm text-center">
                Vous n'appartenez pas à un foyer ?
            </p>
            <p class="font-semibold text-sm text-green_pastel text-center cursor-pointer" @click="toggleDisplay">
                Créer un foyer
            </p>
        </form>

        <span v-if="error" class="text-red-500 text-sm mt-2 block text-center">
            {{ error }}
        </span>

        <i v-if="loading" class="pi pi-spin pi-spinner block text-center mt-2" style="font-size: 2rem"></i>

    </div>
</template>
