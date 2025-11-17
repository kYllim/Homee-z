<script setup lang="ts">
    import { ref } from 'vue';
    import Field from '@/components/Input.vue';

    interface Iconnexionform {
        data : string
    }

    const props = defineProps<Iconnexionform>()

    let error = ref('');
</script>

<template>
    <Field title="elias" name="elias" type="text" placeholder="elias" :data="props.data" icon="pi-lock" :errorMessage="error"/>
</template>


<!-- <div class="flex flex-col gap-5">
<input
    class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
    name="passwordConnexion"
    type="password"
    placeholder="Mot de passe"
/>
</div> -->