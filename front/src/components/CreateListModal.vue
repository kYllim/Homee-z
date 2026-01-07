<template>
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

    <!-- Modal -->
    <div class="bg-white w-full max-w-md rounded-3xl shadow-xl p-8 relative">

      <!-- Close button -->
      <button
        @click="$emit('close')"
        class="absolute top-4 right-4 h-8 w-8 flex justify-center items-center bg-gray-100 hover:bg-gray-200 rounded-full text-black"
      >
        ✕
      </button>

      <!-- Title -->
      <div class="flex items-center gap-3 mb-6">
        <div class="h-12 w-12 bg-[#9CBFA2] rounded-full flex items-center justify-center text-white text-2xl">
          🛒
        </div>
        <h2 class="text-2xl font-extrabold text-gray-800">Nouvelle Liste de Course</h2>
      </div>

      <!-- Form -->
      <form @submit.prevent="create">
        <label class="font-medium text-gray-600">Nom de la liste</label>
        <input
          v-model="title"
          type="text"
          placeholder="Ma liste de course..."
          class="w-full mt-1 mb-5 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#9CBFA2] text-black"
        />

        <label class="font-medium text-gray-600">Articles</label>

        <div class="flex gap-3 mt-1">
          <input
            v-model="articleInput"
            type="text"
            placeholder="Ajouter un article..."
            class="flex-1 p-3 border border-gray-300 rounded-xl text-black"
          />

          <button
            type="button"
            @click="addArticle"
            class="bg-[#9CBFA2] text-white h-12 w-12 flex justify-center items-center rounded-xl text-2xl"
          >
            +
          </button>
        </div>

        <!-- Compteur -->
        <div class="text-sm text-gray-500 mt-1 mb-4">{{ articles.length }} article(s)</div>

        <!-- List preview -->
        <ul class="space-y-1 mb-6">
          <li v-for="(item, i) in articles" :key="i" class="text-gray-700">{{ item }}</li>
        </ul>

        <!-- Buttons -->
        <div class="flex justify-between mt-6">
          <button
            type="button"
            @click="$emit('close')"
            class="w-32 py-3 rounded-xl border border-gray-300 text-gray-600 font-medium"
          >
            Annuler
          </button>

          <button
            type="submit"
            class="w-40 py-3 rounded-xl bg-[#9CBFA2] text-white font-semibold"
          >
            Créer la liste
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue"
import axios from "axios"
import { SHOPPING_API_BASE } from "../services/api"

const emit = defineEmits(["close", "created"])

const title = ref("")
const articleInput = ref("")
const articles = ref<string[]>([])

const addArticle = () => {
  if (!articleInput.value.trim()) return
  articles.value.push(articleInput.value)
  articleInput.value = ""
}

const create = async () => {
  if (!title.value.trim()) return alert("Nom requis")

  await axios.post(`${SHOPPING_API_BASE}/api/shopping-lists`, {
    title: title.value,
    items: articles.value
  })

  emit("created")  // ← Recharge dans ta page
  emit("close")    // ← Ferme le popup
}
</script>
