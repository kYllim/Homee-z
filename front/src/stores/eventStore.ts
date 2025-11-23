import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Event } from '@/models/Events.interface'
import { 
  getEvents, 
  getEventById, 
  createEvent, 
  updateEvent, 
  deleteEvent 
} from '@/services/eventService'

export const useEventStore = defineStore('eventStore', () => {
  const events = ref<Event[]>([])
  const currentEvent = ref<Event | null>(null)

  /**
   * Récupérer tous les événements
   */
  const fetchEvents = async () => {
    try {
      events.value = await getEvents()
    } catch (error) {
      console.error('Erreur lors du chargement des événements', error)
    }
  }

  /**
   * Récupérer un événement par ID
   */
  const fetchEventById = async (id: number) => {
    try {
      currentEvent.value = await getEventById(id)
    } catch (error) {
      console.error('Erreur lors de la récupération de l’événement', error)
    }
  }

  /**
   * Ajouter un nouvel événement
   */
  const addEvent = async (eventData: Partial<Event>) => {
    try {
      const newEvent = await createEvent(eventData)
      events.value.push(newEvent)
    } catch (error) {
      console.error('Erreur lors de la création', error)
    }
  }

  /**
   * Modifier un événement existant
   */
  const updateEventById = async (id: number, eventData: Partial<Event>) => {
    try {
      const updated = await updateEvent(id, eventData)
      const index = events.value.findIndex(e => e.id === id)
      if (index !== -1) events.value[index] = updated
      // Met à jour currentEvent si c’est le même
      if (currentEvent.value?.id === id) currentEvent.value = updated
    } catch (error) {
      console.error('Erreur lors de la modification', error)
    }
  }

  /**
   * Supprimer un événement
   */
  const removeEvent = async (id: number) => {
    try {
      await deleteEvent(id)
      events.value = events.value.filter(e => e.id !== id)
      if (currentEvent.value?.id === id) currentEvent.value = null
    } catch (error) {
      console.error('Erreur lors de la suppression', error)
    }
  }

  return {
    events,
    currentEvent,
    fetchEvents,
    fetchEventById,
    addEvent,
    updateEventById,
    removeEvent
  }
})
