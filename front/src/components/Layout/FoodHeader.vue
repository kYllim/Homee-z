<template>
  <div class="w-full max-w-7xl mx-auto px-4 lg:px-0 mb-10">

    <!-- Titre + boutons -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
      <div>
        <h1 class="text-3xl text-gray-800">Recettes</h1>
        <p class="text-gray-500 text-sm mt-1">
          Gérez vos recettes et listes de courses
        </p>
      </div>

      <div class="flex gap-3">
        <button
          @click="createRecipe"
          class="flex items-center gap-2 bg-green_pastel text-white px-4 py-2 rounded-xl shadow hover:opacity-80 transition"
        >
          + Nouvelle recette
        </button>

        <button
          @click="showListModal = true"
          class="flex items-center gap-2 bg-gray-200 text-gray-700 px-4 py-2 rounded-xl shadow hover:bg-gray-300 transition"
        >
          + Nouvelle liste
        </button>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white rounded-2xl shadow p-5">
        <p class="text-gray-500 text-sm mb-1">Recettes totales</p>
        <p class="text-2xl font-bold">{{ recipesCount }}</p>
      </div>

      <div class="bg-white rounded-2xl shadow p-5">
        <p class="text-gray-500 text-sm mb-1">Listes actives</p>
        <p class="text-2xl font-bold">{{ shoppingListsCount }}</p>
      </div>

      <div class="bg-white rounded-2xl shadow p-5">
        <p class="text-gray-500 text-sm mb-1">Favoris</p>
        <p class="text-2xl font-bold">0</p>
      </div>

      <div class="bg-white rounded-2xl shadow p-5">
        <p class="text-gray-500 text-sm mb-1">Cette semaine</p>
        <p class="text-2xl font-bold">5</p>
      </div>
    </div>

    <!-- Modal création liste -->
    <CreateListModal
      v-if="showListModal"
      @close="showListModal = false"
      @created="onListCreated"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useRouter } from "vue-router"
import api from "@/services/api"
import CreateListModal from "@/components/CreateListModal.vue"

const router = useRouter()

const shoppingListsCount = ref(0)
const recipesCount = ref(0)
const showListModal = ref(false)

const loadCounts = async () => {
  try {
    const [listsRes, recipesRes] = await Promise.all([
      api.get("/shopping-lists"),
      api.get("/recipes"),
    ])

    shoppingListsCount.value = listsRes.data.length
    recipesCount.value = recipesRes.data.length
  } catch (e) {
    console.error("Erreur chargement stats", e)
  }
}

const createRecipe = async () => {
  try {
    const res = await api.post("/recipes", {
      title: "Nouvelle recette",
      instructions: ""
    })

    router.push(`/recipes/${res.data.id}`)
  } catch (err) {
    console.error("Erreur création recette", err)
    alert("Impossible de créer la recette")
  }
}

const onListCreated = async () => {
  showListModal.value = false
  await loadCounts()
}

onMounted(loadCounts)
</script>
