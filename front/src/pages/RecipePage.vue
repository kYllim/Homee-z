<template>
  <div class="min-h-screen bg-gray-50">
    <NavBarConnect />
    <FoodHeader />

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 p-8">

      <!-- Colonne recettes -->
      <div class="lg:col-span-2">
        <h1 class="text-3xl font-bold mb-6">Mes recettes</h1>

        <RecipesGrid
          v-if="recipes.length"
          :recipes="recipes"
        />

        <div
          v-else
          class="bg-white rounded-2xl p-10 text-center text-gray-400"
        >
          Aucune recette pour le moment 🍽️
        </div>
      </div>

      <!-- Colonne droite -->
      <div>
        <ShoppingListBlock />
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'

import NavBarConnect from "@/components/Layout/NavBarConnect.vue"
import ShoppingListBlock from "@/components/ShoppingListBlock.vue"
import FoodHeader from "@/components/Layout/FoodHeader.vue"
import RecipesGrid from "@/components/recipes/RecipesGrid.vue"

const recipes = ref([])

onMounted(async () => {
  const res = await api.get('/recipes')
  recipes.value = res.data
})
</script>
