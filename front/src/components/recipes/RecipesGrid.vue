<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    <RecipeCard
      v-for="recipe in recipes"
      :key="recipe.id"
      :recipe="recipe"
      @click="go(recipe.id)"
    />
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue"
import api from "@/services/api"
import { useRouter } from "vue-router"
import RecipeCard from "./RecipeCard.vue"

const recipes = ref([])
const router = useRouter()

const go = id => router.push(`/recipes/${id}`)

onMounted(async () => {
  const res = await api.get("/recipes")
  recipes.value = res.data
})
</script>
