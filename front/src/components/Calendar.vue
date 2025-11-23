<script setup lang="ts">
import { ref, watch } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import type { EventInput } from '@fullcalendar/core'
import frLocale from '@fullcalendar/core/locales/fr'

// Props : événements et callback dateClick
const props = defineProps<{
  events: EventInput[],
  onDateClick?: (arg: any) => void
}>()

// Options du calendrier
const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  locale: frLocale,
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,dayGridWeek,dayGridDay'
  },
  selectable: true,
  dateClick: (arg: any) => props.onDateClick?.(arg),
  events: props.events,
  eventClassNames: ['bg-primary', 'text-white', 'rounded-md', 'px-1', 'py-0.5', 'border-0'],
  dayCellClassNames: ['cursor-pointer', 'hover:bg-gray-50', 'rounded-lg', 'border-0'] 
})

// Watch pour mettre à jour les events si props.events change
watch(
  () => props.events,
  (newEvents) => {
    calendarOptions.value.events = newEvents
  },
  { deep: true }
)
</script>

<template>
  <div class="calendar-component">
    <header>
      <h2>Mon calendrier</h2>
    </header>

    <FullCalendar :options="calendarOptions" />
  </div>
</template>

<style scoped>
.calendar-component {
  max-width: 900px;
  margin: 0 auto;
}
</style>