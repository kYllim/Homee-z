<script setup>
const props = defineProps({
  chore: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['view-details', 'update-status', 'delete'])

const statusConfig = {
  'in-progress': { bg: 'bg-orange-100', text: 'text-orange-600', label: 'En cours' },
  'todo': { bg: 'bg-gray-100', text: 'text-gray-600', label: 'À faire' },
  'done': { bg: 'bg-green-100', text: 'text-green-600', label: 'Terminé' },
  'overdue': { bg: 'bg-red-100', text: 'text-red-600', label: 'En retard' }
}

const typeConfig = {
  cleaning: { bg: 'bg-[#9CBFA2]', icon: 'fa-broom' },
  cooking: { bg: 'bg-blue-100', icon: 'fa-utensils', iconColor: 'text-blue-600' },
  laundry: { bg: 'bg-green-100', icon: 'fa-check', iconColor: 'text-green-600' },
  shopping: { bg: 'bg-red-100', icon: 'fa-shopping-cart', iconColor: 'text-red-600' },
  garden: { bg: 'bg-purple-100', icon: 'fa-seedling', iconColor: 'text-purple-600' }
}

const getTypeConfig = (type) => typeConfig[type] || typeConfig.cleaning
const getStatusConfig = (status) => statusConfig[status] || statusConfig.todo
</script>

<template>
  <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200 hover:-translate-y-1">
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
      <div class="flex items-center space-x-3">
        <div 
          :class="[
            'w-10 h-10 rounded-xl flex items-center justify-center',
            getTypeConfig(chore.type).bg,
            getTypeConfig(chore.type).iconColor ? '' : 'bg-opacity-20'
          ]"
        >
          <i 
            :class="[
              'fa-solid',
              getTypeConfig(chore.type).icon,
              getTypeConfig(chore.type).iconColor || 'text-green_pastel'
            ]"
          ></i>
        </div>
        <div>
          <h3 class="font-semibold text-[#333333]">{{ chore.title }}</h3>
          <p class="text-xs text-gray-500">{{ chore.category }}</p>
        </div>
      </div>
      <span 
        :class="[
          'text-xs px-2 py-1 rounded-lg font-medium',
          getStatusConfig(chore.status).bg,
          getStatusConfig(chore.status).text
        ]"
      >
        {{ getStatusConfig(chore.status).label }}
      </span>
    </div>
    
    <!-- Description -->
    <p class="text-sm text-gray-600 mb-4">{{ chore.description }}</p>
    
    <!-- Dates -->
    <div class="space-y-2 mb-4">
      <div class="flex items-center justify-between text-xs">
        <span class="text-gray-500">Début :</span>
        <span class="font-medium">{{ chore.startDate }}</span>
      </div>
      <div class="flex items-center justify-between text-xs">
        <span class="text-gray-500">Fin :</span>
        <span 
          :class="[
            'font-medium',
            chore.status === 'overdue' ? 'text-red-600' : 
            chore.status === 'in-progress' ? 'text-orange-600' :
            chore.status === 'done' ? 'text-green-600' : ''
          ]"
        >
          {{ chore.endDate }}
        </span>
      </div>
    </div>
    
    <!-- Footer -->
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <img 
          :src="chore.assignee.avatar" 
          :alt="chore.assignee.name" 
          class="w-6 h-6 rounded-full object-cover"
        />
        <span class="text-xs text-gray-600">{{ chore.assignee.name }}</span>
      </div>
      <button 
        @click="$emit('view-details', chore)"
        class="text-green_pastel hover:text-green-600 text-sm font-medium"
      >
        Voir détails
      </button>
    </div>
  </div>
</template>