<script setup lang="ts">
import { onMounted, computed, ref, onUnmounted } from 'vue'
import type { EventInput } from '@fullcalendar/core'
import { useEventStore } from '@/stores/eventStore'
import Calendar from '@/components/Calendar.vue'
import EventModal from '@/components/event/EventModal.vue'
import NavBarConnect from '@/components/Layout/NavBarConnect.vue'
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

const getEventColor = (status: string) => {
  switch (status?.toLowerCase()) {
    case 'terminé':
    case 'termine':
    case 'completed':
      return '#9CBFA2' // vert doux
    case 'en cours':
    case 'in_progress':
      return '#89AD91' // nuance intermédiaire
    case 'prévu':
    case 'prevu':
    case 'planned':
      return '#ecf4ef' // fond clair
    case 'en retard':
    case 'retard':
    case 'late':
      return '#dc3545' // rouge pour alerte
    case 'annulé':
    case 'annule':
    case 'cancelled':
      return '#4b5563' // gris plus contrasté pour annulé
    default:
      return '#9CBFA2'
  }
}

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
      backgroundColor: getEventColor(computedStatus),
      borderColor: getEventColor(computedStatus),
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
  <div class="page-container">
    <header class="w-full px-4 mb-6 relative z-50">
      <NavBarConnect />
    </header>

    <h2>Mon calendrier</h2>
    <Calendar 
      :events="calendarEvents" 
      :onDateClick="handleDateClick" 
      @eventClick="handleEventClick"
    />

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
.page-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.instructions {
  background: #ecf4ef;
  padding: 15px 20px;
  border-radius: 8px;
  margin-bottom: 20px;
  border-left: 4px solid #9CBFA2;
}

.instructions p {
  margin: 0;
  color: #2c3e50;
  font-size: 14px;
}

/* Styles personnalisés pour FullCalendar */
:deep(.fc-toolbar.fc-header-toolbar) {
  margin-bottom: 1.5rem;
}

:deep(.fc .fc-toolbar-title) {
  color: #2f4a35;
  font-weight: 700;
}

:deep(.fc .fc-button-primary) {
  background-color: #9CBFA2;
  border-color: #9CBFA2;
  font-weight: 600;
}

:deep(.fc .fc-button-primary:not(:disabled).fc-button-active),
:deep(.fc .fc-button-primary:not(:disabled):active) {
  background-color: #89AD91;
  border-color: #89AD91;
}

:deep(.fc .fc-button-primary:disabled) {
  background-color: #c9dccd;
  border-color: #c9dccd;
}

:deep(.fc .fc-button-primary:focus) {
  box-shadow: 0 0 0 3px rgba(156, 191, 162, 0.25);
}

:deep(.fc-daygrid-day-frame) {
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 100%;
  padding: 6px;
  border-radius: 10px;
}

:deep(.fc .fc-daygrid-day-number) {
  float: none !important;
  font-size: 15px;
  font-weight: 600;
  color: #2f4a35;
  padding: 6px 0;
}

:deep(.fc .fc-day-today) {
  background: #ecf4ef !important;
  border: 1px solid #9CBFA2 !important;
}

:deep(.fc-event) {
  border-radius: 10px;
  border-width: 0;
  padding: 6px 8px;
  font-size: 13px;
  font-weight: 600;
}

:deep(.fc-event:focus) {
  outline: none;
  box-shadow: 0 0 0 3px rgba(156, 191, 162, 0.35);
}

:deep(.fc-daygrid-event) {
  margin: 3px 0;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 12px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

:deep(.fc-daygrid-day:hover) {
  background-color: #f5f8f6;
  cursor: pointer;
}
</style>
