import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { Chore } from '@/models/Chore.interface'
import { 
  getChores, 
  getChoreById, 
  createChore, 
  updateChore, 
  deleteChore 
} from '@/services/choreService'

export const useChoreStore = defineStore('choreStore', () => {
  const chores = ref<Chore[]>([])
  const currentChore = ref<Chore | null>(null)
  const currentUserId = ref<number | null>(null)
  const loading = ref(false)
  const error = ref<string | null>(null)

  // Computed pour les statistiques
  const stats = computed(() => ({
    total: chores.value.length,
    done: chores.value.filter(c => c.status === 'done').length,
    inProgress: chores.value.filter(c => c.status === 'in-progress').length,
    overdue: chores.value.filter(c => c.status === 'overdue').length,
    todo: chores.value.filter(c => c.status === 'todo').length
  }))

  /**
   * Définir l'ID de l'utilisateur courant
   */
  const setCurrentUserId = (userId: number) => {
    currentUserId.value = userId
  }

  /**
   * Récupérer toutes les corvées
   */
  const fetchChores = async () => {
    loading.value = true
    error.value = null
    try {
      chores.value = await getChores(currentUserId.value ?? undefined)
    } catch (err) {
      error.value = 'Erreur lors du chargement des corvées'
      console.error('Erreur lors du chargement des corvées', err)
    } finally {
      loading.value = false
    }
  }

  /**
   * Récupérer une corvée par ID
   */
  const fetchChoreById = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      currentChore.value = await getChoreById(id, currentUserId.value ?? undefined)
    } catch (err) {
      error.value = 'Erreur lors de la récupération de la corvée'
      console.error('Erreur lors de la récupération de la corvée', err)
    } finally {
      loading.value = false
    }
  }

  /**
   * Ajouter une nouvelle corvée
   */
  const addChore = async (choreData: Partial<Chore>) => {
    loading.value = true
    error.value = null
    try {
      await createChore(choreData)
      await fetchChores() // Recharger toutes les corvées
    } catch (err) {
      error.value = 'Erreur lors de la création de la corvée'
      console.error('Erreur lors de la création', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Modifier une corvée existante
   */
  const updateChoreById = async (id: number, choreData: Partial<Chore>) => {
    loading.value = true
    error.value = null
    try {
      const updated = await updateChore(id, choreData)
      const index = chores.value.findIndex(c => c.id === id)
      if (index !== -1) chores.value[index] = updated
      if (currentChore.value?.id === id) currentChore.value = updated
    } catch (err) {
      error.value = 'Erreur lors de la modification de la corvée'
      console.error('Erreur lors de la modification', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Supprimer une corvée
   */
  const removeChore = async (id: number) => {
    loading.value = true
    error.value = null
    try {
      await deleteChore(id)
      chores.value = chores.value.filter(c => c.id !== id)
      if (currentChore.value?.id === id) currentChore.value = null
    } catch (err) {
      error.value = 'Erreur lors de la suppression de la corvée'
      console.error('Erreur lors de la suppression', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * Mettre à jour le statut d'une corvée
   */
  const updateChoreStatus = async (id: number, status: string) => {
    await updateChoreById(id, { status })
  }

  return {
    chores,
    currentChore,
    currentUserId,
    loading,
    error,
    stats,
    setCurrentUserId,
    fetchChores,
    fetchChoreById,
    addChore,
    updateChoreById,
    removeChore,
    updateChoreStatus
  }
})
