<script setup>
import { computed } from 'vue'
import ChoreCard from './ChoreCard.vue'

const props = defineProps({
  chores: {
    type: Array,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({})
  }
})

const emit = defineEmits(['view-details', 'update-status', 'delete'])

const filteredChores = computed(() => {
  let result = [...props.chores]
  
  // Filtre par vue (toutes / mes corvées)
  if (props.filters.view === 'mine') {
    result = result.filter(chore => chore.isAssignedToMe)
  }
  
  // Filtre par statut
  if (props.filters.status && props.filters.status !== 'all') {
    result = result.filter(chore => chore.status === props.filters.status)
  }
  
  // Filtre par type
  if (props.filters.type && props.filters.type !== 'all') {
    result = result.filter(chore => chore.type === props.filters.type)
  }
  
  // Filtre par recherche
  if (props.filters.search) {
    const search = props.filters.search.toLowerCase()
    result = result.filter(chore => 
      chore.title.toLowerCase().includes(search) ||
      chore.description.toLowerCase().includes(search)
    )
  }
  
  return result
})
</script>

<template>
  <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <ChoreCard
      v-for="chore in filteredChores"
      :key="chore.id"
      :chore="chore"
      @view-details="$emit('view-details', $event)"
      @update-status="$emit('update-status', $event)"
      @delete="$emit('delete', $event)"
    />
    
    <!-- Message si aucune corvée -->
    <div v-if="filteredChores.length === 0" class="col-span-full text-center py-12">
      <i class="fa-solid fa-inbox text-gray-300 text-6xl mb-4"></i>
      <p class="text-gray-500 text-lg">Aucune corvée trouvée</p>
      <p class="text-gray-400 text-sm">Modifiez vos filtres ou créez une nouvelle corvée</p>
    </div>
  </section>
</template>