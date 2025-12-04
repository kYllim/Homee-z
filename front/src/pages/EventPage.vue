<script setup lang="ts">
import { onMounted, computed, ref } from 'vue'
import type { EventInput } from '@fullcalendar/core'
import { useEventStore } from '@/stores/eventStore'
import Calendar from '@/components/Calendar.vue'
import EventForm from '@/components/event/EventForm.vue'
import type { Event } from '@/models/Events.interface'
import axios from 'axios'

// Ajouter après `eventStore` et `selectedEvent`
const user = ref<any>(null)

const fetchUser = async () => {
  try {
    const { data } = await axios.get(`http://localhost:8000/api/users/${userId}`)
    user.value = data
    console.log('Foyer principal :', data.household?.name)
  } catch (error) {
    console.error(error)
  }
}

// Dans le onMounted, charger aussi l'utilisateur
onMounted(() => {
  eventStore.fetchEvents()
  fetchUser()
})


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
   <div v-if="user">
  <p>{{ user.firstName }} {{ user.lastName }}</p>
  <p>Foyer : {{ user.userHouseholds?.[0]?.household?.name || 'Aucun foyer' }}</p>
</div>


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
