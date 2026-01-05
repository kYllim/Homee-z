<script setup>
import { ref } from 'vue'

const filters = ref({
  view: 'all', // 'all' ou 'mine'
  status: 'all',
  type: 'all',
  search: ''
})

const emit = defineEmits(['filter-change'])

const handleFilterChange = () => {
  emit('filter-change', filters.value)
}
</script>

<template>
  <section class="bg-white rounded-2xl p-6 mb-8 shadow-sm border border-gray-100">
    <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-4">
      <!-- Affichage -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">
        <label class="text-sm font-medium text-gray-700">Affichage :</label>
        <div class="flex bg-gray-100 rounded-lg p-1">
          <button
            @click="filters.view = 'all'; handleFilterChange()"
            :class="[
              'px-4 py-2 text-sm font-medium rounded-md transition flex-1 sm:flex-none',
              filters.view === 'all'
                ? 'bg-green_pastel text-white'
                : 'text-gray-600 hover:text-[#333333]'
            ]"
          >
            Toutes les corvées
          </button>
          <button
            @click="filters.view = 'mine'; handleFilterChange()"
            :class="[
              'px-4 py-2 text-sm font-medium rounded-md transition flex-1 sm:flex-none',
              filters.view === 'mine'
                ? 'bg-green_pastel text-white'
                : 'text-gray-600 hover:text-[#333333]'
            ]"
          >
            Mes corvées
          </button>
        </div>
      </div>

      <!-- Statut -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
        <label class="text-sm font-medium text-gray-700">Statut :</label>
        <select
          v-model="filters.status"
          @change="handleFilterChange"
          class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green_pastel focus:border-transparent w-full sm:w-auto"
        >
          <option value="all">Tous les statuts</option>
          <option value="todo">À faire</option>
          <option value="in-progress">En cours</option>
          <option value="done">Terminé</option>
          <option value="overdue">En retard</option>
        </select>
      </div>

      <!-- Type -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
        <label class="text-sm font-medium text-gray-700">Type :</label>
        <select
          v-model="filters.type"
          @change="handleFilterChange"
          class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green_pastel focus:border-transparent w-full sm:w-auto"
        >
          <option value="all">Tous les types</option>
          <option value="cleaning">Ménage</option>
          <option value="cooking">Cuisine</option>
          <option value="garden">Jardin</option>
          <option value="laundry">Linge</option>
          <option value="shopping">Courses</option>
        </select>
      </div>

      <!-- Recherche -->
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
        <label class="text-sm font-medium text-gray-700">Recherche :</label>
        <div class="relative w-full sm:w-64">
          <input
            v-model="filters.search"
            @input="handleFilterChange"
            type="text"
            placeholder="Rechercher une corvée..."
            class="border border-gray-200 rounded-lg pl-10 pr-4 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green_pastel focus:border-transparent w-full"
          />
          <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </div>
      </div>
    </div>
  </section>
</template>