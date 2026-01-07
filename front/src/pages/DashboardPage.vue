<script setup lang="ts">
  import { onMounted, computed, ref, onUnmounted, watch } from 'vue'
  import Calendar from '@/components/Calendar.vue'
  import { useEventStore } from '@/stores/eventStore'
  import NavBarConnect from "@/components/Layout/NavBarConnect.vue";
  import ToDoList from "@/components/ToDoList.vue";
  import type { Event } from '@/models/Events.interface'
  import type { EventInput } from '@fullcalendar/core'

  // Récupération du store
  const eventStore = useEventStore()
  const statusRefreshTrigger = ref(0)

  // On monte le store pour charger les events
  onMounted(() => {
    // Forcer le chargement des événements au montage
    eventStore.fetchEvents()
    
    const intervalId = setInterval(() => {
      statusRefreshTrigger.value++
    }, 30000)
    
    // Écouter les changements de visibilité pour recharger si la page revient au focus
    const handleVisibilityChange = () => {
      if (!document.hidden) {
        eventStore.fetchEvents()
      }
    }
    document.addEventListener('visibilitychange', handleVisibilityChange)
    
    onUnmounted(() => {
      clearInterval(intervalId)
      document.removeEventListener('visibilitychange', handleVisibilityChange)
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

  // On transforme les events du backend → EventInput pour le calendrier
  const calendarEvents = computed<EventInput[]>(() => {
    statusRefreshTrigger.value
    
    return eventStore.events.map(e => {
      const computedStatus = calculateStatus(e)
      return {
        id: String(e.id),
        title: e.title,
        start: e.startAt,
        end: e.endAt,
        extendedProps: {
          status: computedStatus,
          description: e.description
        }
      }
    })
  })
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-50">
      <NavBarConnect />
      <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

          <!-- To-Do List -->
          <ToDoList />

          <!-- Reminders -->
          <div class="bg-white shadow-sm rounded-2xl p-6">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-lg font-semibold text-gray-800">Rappels</h2>
              <p>(À venir)</p>
            </div>
          </div>
        </div>
        <div class="bg-white shadow-sm rounded-2xl p-6 mt-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Planning</h2>
          <Calendar :events="calendarEvents" />
        </div>
      </div>
    </div>
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


