<script setup lang="ts">
    import { ref,watch } from "vue";
    import type { Ref } from "vue";
    import { useRouter,useRoute } from "vue-router";

    const props = defineProps({
        mode: {
        type: String,
        default: "connexion",
        }
    });

    const router = useRouter();
    const route = useRoute();

</script>

<template>
    <div class="p-4">
    </div>
</template>