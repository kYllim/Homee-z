<script setup lang="ts">
import { ref } from "vue";
import type { Ref } from "vue";
import type { FormData } from "@/model";

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
  <form class="p-6">
    <!-- Connexion -->
    <div v-if="FormType === 'connexion'" class="flex flex-col gap-5">
      <h1 class="text-[25px] font-bold">Connexion</h1>
      <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="emailConnexion"
        type="email"
        placeholder="Email"
        v-model.lazy.trim="FormRef.emailConnexion.value"
      />
      <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="passwordConnexion"
        type="password"
        placeholder="Mot de passe"
        v-model.lazy.trim="FormRef.passwordConnexion.value"
      />
    </div>
    <!-- Inscription -->
    <div v-else class="flex flex-col gap-5">
      <h1 class="text-[25px] font-bold">Inscription</h1>
      <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="name"
        type="text"
        placeholder="Nom"
        v-model.lazy.trim="FormRef.name.value"
      />
      <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="firstName"
        type="text"
        placeholder="Prénom"
        v-model.lazy.trim="FormRef.firstName.value"
      />
      <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        ame="emailRegister"
        type="email"
        placeholder="Email"
        v-model.lazy.trim="FormRef.emailRegister.value"
      />
      <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="passwordRegister"
        type="password"
        placeholder="Mot de passe"
        v-model.lazy.trim="FormRef.passwordRegister.value"
      />
      <input
        class="border-solid border-2 border-black px-5 py-3 rounded-[20px]"
        name="passwordComfirmation"
        type="password"
        placeholder="Comfirmer votre mot de passe"
        v-model.lazy.trim="FormRef.passwordComfirmation.value"
      />
    </div>
    <button
      class="bg-green_pastel text-white font-semibold text-sm px-[22px] py-4 rounded-[20px] flex justify-self-center my-4"
    >
      Se connecter
    </button>
    <!-- Connexion -->
    <p v-if="FormType === 'connexion'" class="text-sm text-center">
      Pas encore de compte ?
      <span class="text-green_pastel" @click="FormType = 'inscription'"
        >Inscrivez-vous</span
      >
    </p>
    <!-- Inscription -->
    <p v-else class="text-sm text-center">
      Déjà membre ?
      <span class="text-green_pastel" @click="FormType = 'connexion'"
        >Connectez-vous</span
      >
    </p>
  </form>
</template>
