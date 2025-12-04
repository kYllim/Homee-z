<script setup lang="ts">
import { ref,watch } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import listPlugin from '@fullcalendar/list'
import type { EventInput } from '@fullcalendar/core'
import frLocale from '@fullcalendar/core/locales/fr'


const props = defineProps<{
  events: EventInput[],
  onDateClick?: (arg: any) => void
}>()


const showModal = ref(false)
const selectedEvent = ref<EventInput | null>(null)

// Fonction pour fermer le modal
const closeModal = () => {
  showModal.value = false
  selectedEvent.value = null
}

// Options FullCalendar
const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin, listPlugin],
  locale: frLocale,
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,dayGridWeek,dayGridDay,listWeek'
  },
  selectable: true,
  dateClick: (arg: any) => props.onDateClick?.(arg),
  events: props.events,
  eventClick: (arg: any) => {
    // On récupère les infos de l'événement cliqué
    selectedEvent.value = {
      ...arg.event.extendedProps,
      title: arg.event.title,
      start: arg.event.start,
      end: arg.event.end
    }
    showModal.value = true
  },
  eventClassNames: ['bg-primary', 'text-white', 'rounded-md', 'px-1', 'py-0.5', 'border-0'],
  dayCellClassNames: ['cursor-pointer', 'hover:bg-gray-50', 'rounded-lg', 'border-0']
})

// Watch pour mettre à jour les events si props.events change
watch(
  () => props.events,
  (newEvents) => {
    calendarOptions.value.events = newEvents
  }
)
</script>

<template>
  <div class="calendar-component">
    <header>
      <h2>Mon calendrier</h2>
    </header>

    <FullCalendar :options="calendarOptions" />

    <!-- Modal popup -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal-content">
        <h3>{{ selectedEvent?.title }}</h3>
        <p><strong>Start:</strong> {{ selectedEvent?.start }}</p>
        <p><strong>End:</strong> {{ selectedEvent?.end }}</p>
        <p v-if="selectedEvent?.description"><strong>Description:</strong> {{ selectedEvent?.description }}</p>
        <button @click="closeModal">Fermer</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.calendar-component {
  max-width: 900px;
  margin: 0 auto;
}

/* Styles du modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  max-width: 400px;
  width: 90%;
  box-shadow: 0 4px 10px rgba(0,0,0,0.25);
}
</style>
