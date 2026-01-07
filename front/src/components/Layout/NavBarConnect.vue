<script setup lang="ts">
import { ref, computed } from "vue";
import { RouterLink, useRouter } from "vue-router";
import type { Ref } from "vue";
import { RemoveCookie } from "@/services";
import { useAuth } from "@/composable/useAuth";

const IsOpen: Ref<boolean> = ref(false);

// 🔐 À remplacer par ton vrai état d'authentification (localStorage, Pinia…)
const isLoggedIn: Ref<boolean> = ref(true);

// Menu utilisateur
const userMenuOpen = ref(false);
const router = useRouter();
const { getUserFromToken } = useAuth();

// Récupérer les infos de l'utilisateur depuis le token
const currentUser = computed(() => getUserFromToken());
const userName = computed(() => {
  if (!currentUser.value) return 'Moi';
  return currentUser.value.fullName || currentUser.value.firstName || currentUser.value.email || 'Moi';
});

// Récupérer les initiales en minuscules
const userInitials = computed(() => {
  if (!currentUser.value) return 'u';
  const firstName = currentUser.value.firstName || '';
  const lastName = currentUser.value.lastName || '';
  if (firstName && lastName) {
    return (firstName.charAt(0) + lastName.charAt(0)).toLowerCase();
  }
  if (firstName) return firstName.charAt(0).toLowerCase();
  if (currentUser.value.email) return currentUser.value.email.charAt(0).toLowerCase();
  return 'u';
});

const toggleUserMenu = () => {
  userMenuOpen.value = !userMenuOpen.value;
};

const Logout = () => {
  RemoveCookie('token');
  router.push('/Connexion');
}


</script>

<template>
  <div>
    <nav class="flex flex-row justify-between p-2 text-base items-center relative lg:px-6">
      <!-- Left -->
      <div class="flex gap-2 items-center">
        <i class="pi pi-times text-xl lg:hidden" @click="IsOpen=false" v-if="IsOpen"></i>
        <i class="pi pi-bars text-xl lg:hidden" @click="IsOpen=true" v-else></i>

        <h1 class="text-lg lg:text-2xl">Homeez</h1>
      </div>

      <!-- Center (desktop only) -->
      <div class="hidden lg:flex gap-6 items-center">
        <template v-if="isLoggedIn">
          <RouterLink to="/dashboard" class="text-lg hover:text-green_pastel">Tableau de bord</RouterLink>
          <RouterLink to="/recettes" class="text-lg hover:text-green_pastel">Recettes</RouterLink>
          <RouterLink to="/chores" class="text-lg hover:text-green_pastel">Tâches</RouterLink>
          <RouterLink to="/events" class="text-lg hover:text-green_pastel">Calendrier</RouterLink>
          <RouterLink to="/familly" class="text-lg hover:text-green_pastel">Famille</RouterLink>
        </template>

        <template v-else>
          <RouterLink to="/" class="text-lg hover:text-green_pastel">Accueil</RouterLink>
          <RouterLink to="/" class="text-lg hover:text-green_pastel">À propos</RouterLink>
          <RouterLink to="/contact" class="text-lg hover:text-green_pastel">Contact</RouterLink>
        </template>
      </div>

      <!-- Right -->
      <div class="flex gap-2 text-sm items-center lg:gap-6">
        <!-- NON CONNECTÉ -->
        <template v-if="!isLoggedIn">
          <RouterLink to="/ConnexionPage" class="font-semibold lg:text-lg hover:text-green_pastel">Connexion</RouterLink>
          <RouterLink to="/InscriptionPage" class="px-3 py-2 bg-green_pastel rounded-sm text-white font-semibold lg:text-lg lg:px-4 hover:opacity-80">
            S'inscrire
          </RouterLink>
        </template>

        <!-- CONNECTÉ -->
        <template v-else>
          <div class="relative">
            <button @click="toggleUserMenu" class="flex flex-col items-center gap-1 hover:opacity-80">
              <div class="w-8 h-8 lg:w-10 lg:h-10 rounded-full bg-gray-200 flex items-center justify-center font-semibold text-gray-700 text-sm lg:text-base">
                {{ userInitials }}
              </div>
              <span class="text-xs lg:text-sm">{{ userName }}</span>
            </button>

            <div
              v-if="userMenuOpen"
              class="absolute right-0 top-16 lg:top-20 bg-white shadow-md rounded-md p-3 flex flex-col gap-2 w-40 z-50"
            >
              <RouterLink to="/profil" class="hover:text-green_pastel font-medium">Profil</RouterLink>
              <RouterLink to="/settings" class="hover:text-green_pastel font-medium">Paramètres</RouterLink>
              <button class="text-left hover:text-red-500 font-medium" @click="Logout">
                Déconnexion
              </button>
            </div>
          </div>
        </template>
      </div>
    </nav>


    <div>
      <div v-if="IsOpen" class="absolute top-12 left-2 bg-white shadow-md rounded-md p-4 flex flex-col gap-3 lg:hidden">

        <!-- CONNECTÉ -->
        <template v-if="isLoggedIn">
          <RouterLink to="/dashboard" class="font-semibold">Tableau de bord</RouterLink>
          <RouterLink to="/recettes" class="font-semibold">Recettes</RouterLink>
          <RouterLink to="/chores" class="font-semibold">Tâches</RouterLink>
          <RouterLink to="/events" class="font-semibold">Calendrier</RouterLink>
          <RouterLink to="/familly" class="font-semibold">Famille</RouterLink>
          <button class="text-left text-red-500 font-semibold mt-2" @click="Logout">Déconnexion</button>
        </template>

        <!-- NON CONNECTÉ -->
        <template v-else>
          <RouterLink to="/" class="font-semibold">Accueil</RouterLink>
          <RouterLink to="/" class="font-semibold">À propos</RouterLink>
          <RouterLink to="/contact" class="font-semibold">Contact</RouterLink>
        </template>

      </div>
    </div>
  </div>
</template>
