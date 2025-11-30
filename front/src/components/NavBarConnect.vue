<script setup lang="ts">
import { ref } from "vue";
import { RouterLink } from "vue-router";
import type { Ref } from "vue";

const IsOpen: Ref<boolean> = ref(false);

// 🔐 À remplacer par ton vrai état d’authentification (localStorage, Pinia…)
const isLoggedIn: Ref<boolean> = ref(true);

// Menu utilisateur
const userMenuOpen = ref(false);

const toggleUserMenu = () => {
  userMenuOpen.value = !userMenuOpen.value;
};
</script>

<template>
  <div>
    <nav class="flex flex-row justify-between p-2 text-base items-center relative lg:px-6">
      <!-- Left -->
      <div class="flex gap-2 items-center">
        <i class="pi pi-times text-xl lg:hidden" @click="IsOpen=false" v-if="IsOpen"></i>
        <i class="pi pi-bars text-xl lg:hidden" @click="IsOpen=true" v-else></i>

        <h1 class="text-lg lg:text-2xl">homeez</h1>
      </div>

      <!-- Center (desktop only) -->
      <div class="hidden lg:flex gap-6 items-center">
        <template v-if="isLoggedIn">
          <RouterLink to="/dashboard" class="text-lg hover:text-green_pastel">Tableau de bord</RouterLink>
          <RouterLink to="/nourriture" class="text-lg hover:text-green_pastel">Nourriture</RouterLink>
          <RouterLink to="/taches" class="text-lg hover:text-green_pastel">Tâches</RouterLink>
          <RouterLink to="/calendrier" class="text-lg hover:text-green_pastel">Calendrier</RouterLink>
          <RouterLink to="/famille" class="text-lg hover:text-green_pastel">Famille</RouterLink>
        </template>

        <template v-else>
          <RouterLink to="/" class="text-lg hover:text-green_pastel">Accueil</RouterLink>
          <RouterLink to="/about" class="text-lg hover:text-green_pastel">À propos</RouterLink>
          <RouterLink to="/contact" class="text-lg hover:text-green_pastel">Contact</RouterLink>
        </template>
      </div>

      <!-- Right -->
      <div class="flex gap-2 text-sm items-center lg:gap-6">
        <!-- 🔓 NON CONNECTÉ -->
        <template v-if="!isLoggedIn">
          <RouterLink to="/ConnexionPage" class="font-semibold lg:text-lg hover:text-green_pastel">Connexion</RouterLink>
          <RouterLink to="/InscriptionPage" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80">
            S'inscrire
          </RouterLink>
        </template>

        <!-- 🔐 CONNECTÉ -->
        <template v-else>
          <div class="relative">
            <button @click="toggleUserMenu" class="flex items-center gap-2 lg:text-lg hover:text-green_pastel">
              <i class="pi pi-user text-xl"></i>
              Moi
            </button>

            <div
              v-if="userMenuOpen"
              class="absolute right-0 top-10 bg-white shadow-md rounded-md p-3 flex flex-col gap-2 w-40 z-50"
            >
              <RouterLink to="/profil" class="hover:text-green_pastel font-medium">Profil</RouterLink>
              <RouterLink to="/settings" class="hover:text-green_pastel font-medium">Paramètres</RouterLink>
              <button class="text-left hover:text-red-500 font-medium">
                Déconnexion
              </button>
            </div>
          </div>
        </template>
      </div>
    </nav>

    <!-- Mobile menu -->
    <div>
      <div v-if="IsOpen" class="absolute top-12 left-2 bg-white shadow-md rounded-md p-4 flex flex-col gap-3 lg:hidden">

        <!-- CONNECTÉ -->
        <template v-if="isLoggedIn">
          <RouterLink to="/dashboard" class="font-semibold">Tableau de bord</RouterLink>
          <RouterLink to="/nourriture" class="font-semibold">Nourriture</RouterLink>
          <RouterLink to="/taches" class="font-semibold">Tâches</RouterLink>
          <RouterLink to="/calendrier" class="font-semibold">Calendrier</RouterLink>
          <RouterLink to="/famille" class="font-semibold">Famille</RouterLink>
          <button class="text-left text-red-500 font-semibold mt-2">Déconnexion</button>
        </template>

        <!-- NON CONNECTÉ -->
        <template v-else>
          <RouterLink to="/" class="font-semibold">Accueil</RouterLink>
          <RouterLink to="/about" class="font-semibold">À propos</RouterLink>
          <RouterLink to="/contact" class="font-semibold">Contact</RouterLink>
        </template>

      </div>
    </div>
  </div>
</template>


