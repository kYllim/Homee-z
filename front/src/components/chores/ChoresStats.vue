<script setup>
import { computed } from 'vue'

const props = defineProps({
  chores: {
    type: Array,
    required: true
  }
})

const stats = computed(() => ({
  total: props.chores.length,
  done: props.chores.filter(c => c.status === 'done').length,
  inProgress: props.chores.filter(c => c.status === 'in-progress').length,
  overdue: props.chores.filter(c => c.status === 'overdue').length
}))
</script>

<template>
  <section class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- Total -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
      <div class="w-12 h-12 bg-green_pastel bg-opacity-20 rounded-xl flex items-center justify-center mx-auto mb-3">
        <i class="fa-solid fa-list-check text-green_pastel text-xl"></i>
      </div>
      <h3 class="text-2xl font-bold text-[#333333] mb-1">{{ stats.total }}</h3>
      <p class="text-sm text-gray-600">Total corvées</p>
    </div>
    
    <!-- Terminées -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
      <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
        <i class="fa-solid fa-check text-green-600 text-xl"></i>
      </div>
      <h3 class="text-2xl font-bold text-[#333333] mb-1">{{ stats.done }}</h3>
      <p class="text-sm text-gray-600">Terminées</p>
    </div>
    
    <!-- En cours -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
      <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-3">
        <i class="fa-solid fa-clock text-orange-600 text-xl"></i>
      </div>
      <h3 class="text-2xl font-bold text-[#333333] mb-1">{{ stats.inProgress }}</h3>
      <p class="text-sm text-gray-600">En cours</p>
    </div>
    
    <!-- En retard -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
      <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-3">
        <i class="fa-solid fa-exclamation-triangle text-red-600 text-xl"></i>
      </div>
      <h3 class="text-2xl font-bold text-[#333333] mb-1">{{ stats.overdue }}</h3>
      <p class="text-sm text-gray-600">En retard</p>
    </div>
  </section>
</template>