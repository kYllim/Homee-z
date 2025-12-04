<script setup lang="ts">
  import { ref } from "vue";
  import type { Ref } from "vue";
  import type { FormConnexion, ErrorsConexion, FormRegister, ErrorRegister } from "../model";
  import NavBarLanding from "@/components/NavBarLanding.vue";
  import ConnexionForm from "@/components/ConnexionForm.vue";
  import InscriptionForm from "@/components/InscriptionForm.vue";


  const FormType: Ref<"connexion" | "inscription"> = ref("connexion");

  // Ref pour le form de connexion
  const ConnexionFormRef: FormConnexion = {
    emailConnexion: ref(""),
    passwordConnexion: ref(""),
  };
  const ConnexionFormError: ErrorsConexion = {
    emailConnexion: ref(""),
    passwordConnexion: ref(""),
  }

  // Ref pour le form d'inscription
  const RegisterFormRef : FormRegister = {
    emailRegister: ref(""),
    passwordRegister: ref(""),
    passwordComfirmation : ref(""),
    firstName : ref(''),
    name : ref('')
  };
  const RegisterFormError: ErrorRegister = {
    emailRegister : ref(''),
    passwordRegister: ref(""),
    passwordComfirmation: ref(""),
    name: ref(""),
    firstName: ref(""),
  }

  // Gérér l'affichage des forms 
  const toggleDisplay = () => {
    FormType.value =
      FormType.value === "connexion" ? "inscription" : "connexion";
  };

</script>

<template>
  <div>
    <NavBarLanding/>  
    <ConnexionForm v-if="FormType === 'connexion'" :dataConnexion="ConnexionFormRef" :errorConnexion="ConnexionFormError" :toggleDisplay="toggleDisplay"/>
    <InscriptionForm :dataRegister="RegisterFormRef" :errorRegister="RegisterFormError" :toggleDisplay="toggleDisplay" v-else/>
  </div>
</template>
