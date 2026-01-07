<template>
  <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6">Nouvelle recette</h2>

      <form @submit.prevent="create">
        <input
          v-model="title"
          placeholder="Nom de la recette"
          class="w-full p-3 border rounded-xl mb-4"
          required
        />

        <div class="flex justify-end gap-3">
          <button type="button" @click="$emit('close')">Annuler</button>
          <button class="bg-green_pastel text-white px-4 py-2 rounded-xl">
            Créer
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import api from "@/services/api"
import { useRouter } from "vue-router"

const emit = defineEmits(["close"])
const router = useRouter()
const title = ref("")

const create = async () => {
  const res = await api.post("/recipes", { title: title.value })
  router.push(`/recipes/${res.data.id}`)
}
</script>
