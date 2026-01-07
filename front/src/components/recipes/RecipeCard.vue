<template>
  <div class="bg-white rounded-2xl shadow hover:shadow-md transition cursor-pointer overflow-hidden">
    <div class="h-36 bg-gradient-to-br from-green-200 to-green-300 flex items-center justify-center">
      🍽️
    </div>

    <div class="p-4">
      <div class="flex justify-between items-start">
        <h3 class="font-bold text-lg">{{ recipe.title }}</h3>
        <span>♡</span>
      </div>

      <p class="text-sm text-gray-500 mt-1">
        Portions : {{ recipe.servings ?? "—" }}
      </p>

      <div class="flex flex-wrap gap-2 mt-3">
        <span
          v-for="tag in recipe.tags"
          :key="tag"
          class="text-xs px-3 py-1 bg-gray-100 rounded-full"
        >
          {{ tag }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  recipe: Object
})
</script>
