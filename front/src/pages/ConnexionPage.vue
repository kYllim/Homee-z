<script setup lang="ts">
  import { ref } from "vue";
  import type { Ref } from "vue";
  import type FormData from "../interfaces/FormData";

  const FormType: Ref<"connexion" | "inscription"> = ref("connexion");

  let refForm : FormData = {
    name: ref(''),
    firstName: ref(''),
    emailConnexion: ref(''),
    passwordConnexion: ref(''),
    emailRegister: ref(''),
    passwordRegister: ref(''),
    passwordComfirmation: ref(''),
  }

</script>

<template>
  <form class="p-6">
    <!-- Connexion -->
    <div v-if="FormType === 'connexion'" class="flex flex-col gap-5">
      <h1 class="font-bold text-[25px]">Connexion</h1>
      <input  class="p-4 border-solid border-2 rounded-[18px] text-[14px]" v-model:="refForm.emailConnexion" name="emailConnexion" type="email" placeholder="Email" />
      <input
        class="p-4 border-solid border-2 rounded-[18px] text-[14px]"
        name="passwordConnexion"
        type="password"
        placeholder="Mot de passe"
        v-model="refForm.passwordConnexion"
      />
    </div>
    <!-- Inscription -->
    <div v-else>
      <h1>Inscription</h1>
      <input name="name" type="text" placeholder="Nom" v-model="refForm.name"/>
      <input name="firstName" type="text" placeholder="Prénom"  v-model="refForm.firstName"/>
      <input name="emailRegister" type="email" placeholder="Email" v-model="refForm.emailRegister"/>
      <input
        name="passwordRegister"
        type="password"
        placeholder="Mot de passe"
        v-model="refForm.passwordRegister"
      />
      <input
        name="passwordComfirmation"
        type="password"
        placeholder="Comfirmer votre mot de passe"
        v-model="refForm.passwordComfirmation"
      />
    </div>
    <button class="bg-green-test px-8  py-4 rounded-[20px] text-white font-semibold my-3 flex justify-self-center">Se connecter</button>
    <!-- Connexion -->
    <p v-if="FormType === 'connexion'" class="text-center text-[12px]">
      Pas encore de compte ? <span @click="FormType = 'inscription'"
      class="text-green-test underline"  
      >Inscrivez-vous</span
      >
    </p>
    <!-- Inscription -->
    <p v-else class="text-center text-[12px]">
      Déjà membre ? <span @click="FormType = 'connexion'" class="text-green-test underline">Connectez-vous</span>
    </p>
  </form>
</template>
