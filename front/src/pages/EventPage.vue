<script setup lang="ts">
import { onMounted, computed, ref, onUnmounted } from 'vue'
import type { EventInput } from '@fullcalendar/core'
import { useEventStore } from '@/stores/eventStore'
import Calendar from '@/components/Calendar.vue'
import EventModal from '@/components/event/EventModal.vue'
import NavBarConnect from "@/components/Layout/NavBarConnect.vue"
import type { Event } from '@/models/Events.interface'

const eventStore = useEventStore()

const showModal = ref(false)
const modalMode = ref<'create' | 'edit' | 'delete' | 'view'>('create')
const selectedEvent = ref<Event | null>(null)
const selectedDate = ref<string>('')

const statusRefreshTrigger = ref(0)

onMounted(() => {
  eventStore.fetchEvents()
  const intervalId = setInterval(() => {
    statusRefreshTrigger.value++
  }, 30000)
  onUnmounted(() => {
    clearInterval(intervalId)
  })
})

const calculateStatus = (event: Event) => {
  if (event.status && event.status.trim() !== '') {
    return event.status
  }

  const now = new Date()
  const startDate = new Date(event.startAt)
  const endDate = event.endAt ? new Date(event.endAt) : null

  if (startDate > now) return 'prévu'
  if (endDate && endDate < now) return 'en retard'
  return 'en cours'
}

const calendarEvents = computed<EventInput[]>(() => {
  statusRefreshTrigger.value
  
  return eventStore.events.map(e => {
    const computedStatus = calculateStatus(e)
    return {
      title: e.title,
      start: e.startAt,
      end: e.endAt,
      id: String(e.id),
      extendedProps: {
        status: computedStatus,
        description: e.description
      }
    }
  })
})

const handleDateClick = (arg: any) => {
  selectedEvent.value = null
  selectedDate.value = arg.dateStr
  modalMode.value = 'create'
  showModal.value = true
}

const handleModalSave = async (eventData: any) => {
  try {
    if (eventData.id) {
      await eventStore.updateEventById(eventData.id, eventData)
      selectedEvent.value = eventStore.events.find(e => e.id === eventData.id) || null
    } else {
      const dataToSave = selectedDate.value && !eventData.startAt 
        ? { ...eventData, startAt: selectedDate.value }
        : eventData
      console.log('Saving event:', dataToSave)
      await eventStore.addEvent(dataToSave)
    }
    closeModal()
  } catch (error) {
    console.error('Erreur lors de la sauvegarde:', error)
  }
}

const handleEventClick = (arg: any) => {
  const eventId = Number(arg.event.id)
  const e = eventStore.events.find(ev => ev.id === eventId)
  if (e) {
    selectedEvent.value = e
    modalMode.value = 'view'
    showModal.value = true
  }
}

const handleModalDelete = async () => {
  if (selectedEvent.value?.id) {
    await eventStore.removeEvent(selectedEvent.value.id)
    closeModal()
  }
}

const closeModal = () => {
  showModal.value = false
  selectedEvent.value = null
  selectedDate.value = ''
}
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-50">
      <NavBarConnect />
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="bg-white shadow-sm rounded-2xl p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Mon calendrier</h2>
          <p class="text-sm text-gray-600 mb-4">Cliquez sur une date pour créer un événement, ou sur un événement existant pour le modifier</p>
          <Calendar 
            :events="calendarEvents" 
            :onDateClick="handleDateClick" 
            @eventClick="handleEventClick"
          />
        </div>
      </div>
    </div>

    <EventModal 
      v-if="showModal"
      :mode="modalMode"
      :eventData="selectedEvent"
      @close="closeModal"
      @save="handleModalSave"
      @delete="handleModalDelete"
    />
  </div>
</template>

<style scoped>
:deep(.fc-today-button) {
  background-color: #9CBFA2 !important;
  border-color: #9CBFA2 !important;
  color: white !important;
}

:deep(.fc-today-button:hover) {
  background-color: #89AD91 !important;
  border-color: #89AD91 !important;
}

:deep(.fc-today-button:focus) {
  box-shadow: 0 0 0 3px rgba(156, 191, 162, 0.25) !important;
}
</style>
