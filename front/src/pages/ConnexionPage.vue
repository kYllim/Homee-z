<script setup lang="ts">
import { ref } from "vue";
import type { Ref } from "vue";

const FormType: Ref<"connexion" | "inscription"> = ref("connexion");
</script>

<template>
  <form>
    <!-- Connexion -->
    <div v-if="FormType === 'connexion'">
      <h1>Connexion</h1>
      <input name="emailConnexion" type="email" placeholder="Email" />
      <input
        name="passwordConnexion"
        type="password"
        placeholder="Mot de passe"
      />
    </div>
    <!-- Inscription -->
    <div v-else>
      <h1>Inscription</h1>
      <input name="name" type="text" placeholder="Nom" />
      <input name="firstName" type="text" placeholder="Prénom" />
      <input name="emailRegister" type="email" placeholder="Email" />
      <input
        name="passwordRegister"
        type="password"
        placeholder="Mot de passe"
      />
      <input
        name="passwordComfirmation"
        type="password"
        placeholder="Comfirmer votre mot de passe"
      />
    </div>
    <button>Se connecter</button>
    <!-- Connexion -->
    <p v-if="FormType === 'connexion'">
      Pas encore de compte ?<span @click="FormType = 'inscription'"
        >Inscrivez-vous</span
      >
    </p>
    <!-- Inscription -->
    <p v-else>
      Déjà membre ?<span @click="FormType = 'connexion'">Connectez-vous</span>
    </p>
  </form>
</template>
