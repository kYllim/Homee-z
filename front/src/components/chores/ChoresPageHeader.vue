<script setup>
const emit = defineEmits(['create-chore'])
</script>

<template>
  <section class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h2 class="text-3xl font-['Outfit'] font-bold text-[#333333] mb-2">Corvées du foyer</h2>
        <p class="text-gray-600">Gérez et suivez toutes les tâches de votre foyer</p>
      </div>
      <button
        @click="$emit('create-chore')"
        class="bg-[#9CBFA2] hover:bg-green-500 text-white px-6 py-3 rounded-xl font-semibold flex items-center justify-center space-x-2 transition-colors w-full sm:w-auto"
      >
        <i class="fa-solid fa-plus"></i>
        <span>Nouvelle corvée</span>
      </button>
    </div>
  </section>
</template>