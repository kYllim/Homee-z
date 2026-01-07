<script setup lang="ts">
  import { ref } from "vue";
  import { RouterLink } from 'vue-router'
  import type { Ref } from "vue";

  const IsOpen: Ref<boolean> = ref(false);
</script>

<template>
  <div>
    <nav class="flex flex-row justify-between p-2 text-base items-center relative lg:px-6">
      <div class="flex gap-2 items-center">
        <i class="pi pi-times text-xl lg:hidden" @click="IsOpen=false" v-if="IsOpen"></i>
        <i class="pi pi-bars text-xl lg:hidden" @click="IsOpen=true" v-else></i>
        <h1 class="font-bold text-lg lg:text-4xl">homeez</h1>
      </div>
      <div class="hidden lg:flex gap-6 items-center">
        <a href="#" class="font-semibold text-lg hover:text-green_pastel">Fonctionnalités</a>
        <a href="#" class="font-semibold text-lg hover:text-green_pastel">Avantages</a>
        <a href="#" class="font-semibold text-lg hover:text-green_pastel">FAQ</a>
        <a href="#" class="font-semibold text-lg hover:text-green_pastel">Contact</a>
      </div>
      <div class="flex gap-2 text-sm items-center lg:gap-6">
        <router-link :to="{ name : 'Connection', query: { mode: 'connexion'}}" class="font-semibold lg:text-lg hover:text-green_pastel">Connexion</router-link>
        <router-link :to="{ name : 'Connection', query: { mode: 'inscription'}}" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80">S'inscrire</router-link>
      </div>
    </nav>
    <div>
      <div v-if="IsOpen" class="absolute top-12 left-2 bg-white shadow-md rounded-md p-4 flex flex-col gap-3 lg:hidden">
        <router-link to="/" class="font-semibold">Accueil</router-link>
        <router-link to="/" class="font-semibold">À propos</router-link>
        <router-link to="/contact" class="font-semibold">Contact</router-link>
      </div>
    </div>
  </div>
</template>
