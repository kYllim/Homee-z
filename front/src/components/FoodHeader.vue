<template>
  <div class="w-full max-w-7xl mx-auto px-4 lg:px-0 mb-10">

    <!-- Titre + boutons -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
      
      <!-- TITRE -->
      <div>
        <h1 class="text-3xl text-gray-800">Nourriture</h1>
        <p class="text-gray-500 text-sm mt-1">Gérez vos recettes et listes de courses</p>
      </div>

      <!-- BOUTONS -->
      <div class="flex gap-3">
        <button
          class="flex items-center gap-2 bg-[#9CBFA2] text-white px-4 py-2 rounded-xl shadow hover:opacity-80 transition"
        >
          <span>+ Nouvelle recette</span>
        </button>

        <button
          class="flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-xl shadow hover:bg-gray-300 transition"
        >
          <span>+ Nouvelle liste</span>
        </button>
      </div>
    </div>

    <!-- LES 4 BLOCS STATISTIQUES -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

      <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
        <p class="text-gray-500 text-sm mb-1">Recettes totales</p>
        <p class="text-2xl font-bold text-gray-800">0</p>
      </div>

      <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
        <p class="text-gray-500 text-sm mb-1">Listes actives</p>
        <p class="text-2xl font-bold text-gray-800">{{ shoppingListsCount }}</p>
      </div>

      <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
        <p class="text-gray-500 text-sm mb-1">Favoris</p>
        <p class="text-2xl font-bold text-gray-800">0</p>
      </div>

      <div class="bg-white rounded-2xl shadow p-5 border border-gray-100">
        <p class="text-gray-500 text-sm mb-1">Cette semaine</p>
        <p class="text-2xl font-bold text-gray-800">5</p>
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
// Listes de courses
const shoppingListsCount = ref(0)

const loadLists = async () => {
  try {
    const res = await axios.get("http://127.0.0.1:8000/shopping-lists")
    shoppingListsCount.value = res.data.length
  } catch (e) {
    console.error("Erreur chargement listes", e)
  }
}
</script>
