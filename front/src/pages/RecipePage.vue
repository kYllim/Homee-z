<template>
  <div class="min-h-screen bg-gray-50">
    <NavBarConnect />

    <FoodHeader />

    <!-- Grille à 2 colonnes -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 p-8">

      <!-- Colonne principale (recettes) -->
      <div class="lg:col-span-2">
        <h1 class="text-3xl font-bold mb-6">Mes recettes</h1>

        <!-- ICI futur composant recettes -->
        <div class="bg-white rounded-2xl p-6 h-96 flex items-center justify-center text-gray-400">
          EN ATTENTE… (recettes)
        </div>
      </div>

      <!-- Colonne droite : composant Listes de courses -->
      <div>
        <ShoppingListBlock />
      </div>

    </div>
  </div>
</template>

<script setup>
import NavBarConnect from "@/components/Layout/NavBarConnect.vue";
import ShoppingListBlock from "@/components/ShoppingListBlock.vue";
import FoodHeader from "@/components/Layout/FoodHeader.vue";
</script>
