<script setup lang="ts">
  import { ref } from "vue";
  import type { Ref } from "vue";
  import type { FormData } from "../model";
  import NavBarLanding from "@/components/NavBarLanding.vue";
  import ConnexionForm from "@/components/ConnexionForm.vue";

  const FormType: Ref<"connexion" | "inscription"> = ref("connexion");
  const FormRef: FormData = {
    name: ref(""),
    firstName: ref(""),
    emailConnexion: ref(""),
    passwordConnexion: ref(""),
    emailRegister: ref(""),
    passwordRegister: ref(""),
    passwordComfirmation: ref(""),
  };
</script>

<template>
  <div>
    <NavBarLanding/>
    <ConnexionForm :data="FormRef.name.value"/>
    {{ FormRef.name.value }}
  </div>
</template>
