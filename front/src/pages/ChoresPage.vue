<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useChoreStore } from '@/stores/choreStore'
import axios from 'axios'
import ChoresHeader from '@/components/chores/ChoresHeader.vue'
import ChoresPageHeader from '@/components/chores/ChoresPageHeader.vue'
import ChoresFilters from '@/components/chores/ChoresFilters.vue'
import ChoresGrid from '@/components/chores/ChoresGrid.vue'
import ChoresStats from '@/components/chores/ChoresStats.vue'
import ChoreFormModal from '@/components/chores/ChoreFormModal.vue'
import type { Chore } from '@/models/Chore.interface'
import NavBarConnect from '@/components/NavBarConnect.vue'

// Store Pinia
const choreStore = useChoreStore()

// User info
const user = ref<any>(null)
const userId = 2 // TODO: Récupérer depuis l'auth

// Modal state
const isModalOpen = ref(false)
const editingChore = ref<Chore | null>(null)

// Filters state
const currentFilters = ref({
  view: 'all',
  status: 'all',
  type: 'all',
  search: ''
})

/**
 * Charger l'utilisateur courant
 */
const fetchUser = async () => {
  try {
    const { data } = await axios.get(`http://localhost:8000/api/users/${userId}`)
    user.value = data
    choreStore.setCurrentUserId(data.id)
    console.log('Utilisateur chargé:', data)
  } catch (error) {
    console.error('Erreur chargement utilisateur:', error)
  }
}

/**
 * Charger les corvées au montage
 */
onMounted(async () => {
  // await fetchUser() // ⚠️ Commenté temporairement car user n'existe pas
  choreStore.setCurrentUserId(1) // ID fictif pour le moment
  await choreStore.fetchChores()
})

/**
 * Ouvrir le modal de création
 */
const handleCreateChore = () => {
  editingChore.value = null
  isModalOpen.value = true
}

/**
 * Gérer les changements de filtres
 */
const handleFilterChange = (filters: any) => {
  currentFilters.value = filters
}

/**
 * Voir les détails d'une corvée (ouvre le modal en mode édition)
 */
const handleViewDetails = (chore: Chore) => {
  editingChore.value = chore
  isModalOpen.value = true
}

/**
 * Soumettre le formulaire (création ou modification)
 */
const handleSubmitChore = async (choreData: Partial<Chore>) => {
  try {
    if (editingChore.value) {
      // Mode édition
      await choreStore.updateChoreById(editingChore.value.id, choreData)
    } else {
      // Mode création
      await choreStore.addChore(choreData)
    }
    
    // Fermer le modal
    isModalOpen.value = false
    editingChore.value = null
  } catch (error) {
    console.error('Erreur lors de la soumission:', error)
    alert('Une erreur est survenue lors de l\'enregistrement de la corvée')
  }
}

/**
 * Fermer le modal
 */
const handleCloseModal = () => {
  isModalOpen.value = false
  editingChore.value = null
}

/**
 * Mettre à jour le statut d'une corvée
 */
const handleUpdateStatus = async (chore: Chore) => {
  // Cycle de statuts: todo → in-progress → done
  const statusCycle: Record<string, string> = {
    'todo': 'in-progress',
    'in-progress': 'done',
    'done': 'todo',
    'overdue': 'in-progress'
  }
  
  const newStatus = statusCycle[chore.status] || 'todo'
  
  try {
    await choreStore.updateChoreStatus(chore.id, newStatus)
  } catch (error) {
    console.error('Erreur mise à jour statut:', error)
  }
}

/**
 * Supprimer une corvée
 */
const handleDeleteChore = async (chore: Chore) => {
  if (!confirm(`Êtes-vous sûr de vouloir supprimer la corvée "${chore.title}" ?`)) {
    return
  }
  
  try {
    await choreStore.removeChore(chore.id)
  } catch (error) {
    console.error('Erreur suppression:', error)
    alert('Une erreur est survenue lors de la suppression de la corvée')
  }
}
</script>

<template>
  <div class="bg-[#FAFAFA] min-h-screen">
    <!-- Header avec navigation -->
    <NavBarConnect/>
    
    <main class="max-w-7xl mx-auto px-6 py-8">
      <!-- Info utilisateur (optionnel) -->
      <div v-if="user" class="mb-6 bg-white rounded-xl p-4 shadow-sm border border-gray-100">
        <div class="flex items-center space-x-3">
          <img 
            :src="user.avatar || 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-5.jpg'"
            alt="Avatar" 
            class="w-10 h-10 rounded-full object-cover"
          />
          <div>
            <p class="font-semibold text-[#333333]">
              {{ user.firstName }} {{ user.lastName }}
            </p>
            <p class="text-sm text-gray-600">
              Foyer : {{ user.userHouseholds?.[0]?.household?.name || 'Aucun foyer' }}
            </p>
          </div>
        </div>
      </div>

      <!-- En-tête de page avec bouton "Nouvelle corvée" -->
      <ChoresPageHeader @create-chore="handleCreateChore" />
      
      <!-- Filtres de recherche -->
      <ChoresFilters @filter-change="handleFilterChange" />
      
      <!-- Loading indicator -->
      <div v-if="choreStore.loading" class="text-center py-12">
        <i class="fa-solid fa-spinner fa-spin text-[#9CBFA2] text-4xl"></i>
        <p class="text-gray-600 mt-4">Chargement des corvées...</p>
      </div>

      <!-- Error message -->
      <div v-else-if="choreStore.error" class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-center space-x-3">
          <i class="fa-solid fa-exclamation-circle text-red-600 text-xl"></i>
          <p class="text-red-800">{{ choreStore.error }}</p>
        </div>
      </div>

      <!-- Grille de corvées -->
      <ChoresGrid 
        v-else
        :chores="choreStore.chores"
        :filters="currentFilters"
        @view-details="handleViewDetails"
        @update-status="handleUpdateStatus"
        @delete="handleDeleteChore"
      />
      
      <!-- Statistiques -->
      <ChoresStats :chores="choreStore.chores" />
    </main>

    <!-- Modal de création/édition -->
    <ChoreFormModal
      :is-open="isModalOpen"
      :chore="editingChore"
      @close="handleCloseModal"
      @submit="handleSubmitChore"
    />
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap');

::-webkit-scrollbar {
  display: none;
}
</style>