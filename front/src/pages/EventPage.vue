<script setup lang="ts">
import { onMounted, computed, ref } from 'vue'
import type { EventInput } from '@fullcalendar/core'
import { useEventStore } from '@/stores/eventStore'
import Calendar from '@/components/Calendar.vue'
import EventForm from '@/components/event/EventForm.vue'
import type { Event } from '@/models/Events.interface'

// Pinia store
const eventStore = useEventStore()

// Événement sélectionné pour édition
const selectedEvent = ref<Event | null>(null)

// Charger les événements au montage
onMounted(() => {
  eventStore.fetchEvents()
})

// Transformer les événements pour FullCalendar
const calendarEvents = computed<EventInput[]>(() =>
  eventStore.events.map(e => ({
    title: e.title,
    start: e.startAt,
    end: e.endAt,
    id: String(e.id) // FullCalendar attend id en string
  }))
)

// Click sur une date → prépare création
const handleDateClick = (arg: any) => {
  selectedEvent.value = null
  alert('Date cliquée : ' + arg.dateStr)
}

// Ajouter ou modifier un événement
const handleSubmit = (eventData: Partial<Event>) => {
  if (selectedEvent.value?.id) {
    eventStore.updateEventById(selectedEvent.value.id, eventData)
  } else {
    eventStore.addEvent(eventData)
  }
  selectedEvent.value = null
}

// Sélectionner un événement depuis le calendrier
const handleEventClick = (arg: any) => {
  const eventId = Number(arg.event.id)
  const e = eventStore.events.find(ev => ev.id === eventId)
  if (e) selectedEvent.value = e
}

// Supprimer un événement
const handleDelete = (id: number) => {
  if (confirm('Supprimer cet événement ?')) {
    eventStore.removeEvent(id)
    if (selectedEvent.value?.id === id) selectedEvent.value = null
  }
}

// Annuler l'édition
const handleCancel = () => {
  selectedEvent.value = null
}
</script>

<template>
  <div class="page-container">
    <h2>Mon calendrier</h2>

    <!-- Formulaire pour ajouter ou éditer -->
    <EventForm 
      :selectedEvent="selectedEvent" 
      :onSubmit="handleSubmit" 
      :onCancel="handleCancel"
    />

    <!-- Calendrier FullCalendar -->
    <Calendar 
      :events="calendarEvents" 
      :onDateClick="handleDateClick" 
      @eventClick="handleEventClick"
    />

    <!-- Bouton supprimer si un événement est sélectionné -->
    <button v-if="selectedEvent" @click="handleDelete(selectedEvent.id)">
      Supprimer l'événement
    </button>
  </div>
</template>

<style scoped>
.page-container {
  max-width: 900px;
  margin: 0 auto;
}

button {
  margin-top: 10px;
  padding: 5px 10px;
  cursor: pointer;
}
</style>
