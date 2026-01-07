<template>
    <div class="w-full flex justify-center">
  <div class="bg-white w-full max-w-4xl rounded-3xl shadow-xl p-8 relative">

    <!-- Title + Add button -->
    <div class="flex items-center justify-between mb-10">
      <h1 class="text-3xl font-extrabold text-gray-800">Listes de courses</h1>

      <button 
        @click="openModal"
        class="h-10 w-10 flex items-center justify-center bg-[#9CBFA2] text-white rounded-full shadow hover:scale-105 transition"
      >
        +
      </button>
    </div>

    <!-- LISTES -->
    <div class="space-y-8">
      <div
        v-for="list in shoppingLists"
        :key="list.id"
        class="bg-gray-50 rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition cursor-pointer"
      >
        <!-- Title + badge -->
        <div class="flex items-center justify-between mb-3">
          <h2 class="text-xl font-semibold text-gray-800">{{ list.title }}</h2>

          <span
            class="text-xs font-medium px-3 py-1 rounded-full"
            :class="{
              'bg-green-200 text-green-700': list.status === 'active',
              'bg-gray-300 text-gray-700': list.status === 'generated',
              'bg-yellow-100 text-yellow-700': list.status === 'draft'
            }"
          >
            {{ list.status }}
          </span>
        </div>

        <!-- Infos -->
        <p class="text-sm text-gray-500 mb-4">
          {{ list.items?.length }} articles • Créée le {{ formatDate(list.createdAt) }}
        </p>

        <!-- Preview items -->
        <div class="space-y-1 mb-3">
          <div 
            v-for="item in (list.items ?? []).slice(0, 3)"
            :key="item.id"
            class="flex items-center gap-2"
          >
            <input type="checkbox" class="h-4 w-4 rounded border-gray-300" />
            <span class="text-gray-700">{{ item.name }}</span>
          </div>
        </div>

        <button
          @click="goToList(list.id)"
          class="text-[#7CA886] font-medium text-sm hover:underline"
        >
          Voir tous les articles
        </button>
      </div>
    </div>

    <!-- Popup modal -->
    <CreateListModal v-if="showModal" @close="closeModal" @created="loadLists" />
  </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
import { useRouter } from "vue-router"
import CreateListModal from "./CreateListModal.vue"

const shoppingLists = ref([])
const showModal = ref(false)
const router = useRouter()

const openModal = () => showModal.value = true
const closeModal = () => showModal.value = false

const loadLists = async () => {
  try {
    const res = await axios.get("http://127.0.0.1:8001/api/shopping-lists",{})
    shoppingLists.value = res.data
  } catch (error) {
    console.error("Erreur chargement :", error)
  }
}

const goToList = id => router.push(`/shopping-lists/${id}`)

const formatDate = dateString => new Date(dateString).toLocaleDateString("fr-FR")

onMounted(loadLists)
</script>
