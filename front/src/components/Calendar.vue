<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue'
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

const emit = defineEmits(['eventClick'])

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
    // Émettre l'événement au parent au lieu d'afficher un modal
    emit('eventClick', arg)
  },
  eventClassNames: (arg: any) => {
    const status = (arg.event.extendedProps?.status || '').toLowerCase()
    const base = ['rounded-md', 'px-1', 'py-0.5', 'border-0', 'text-xs']
    switch (status) {
      case 'terminé':
      case 'termine':
      case 'completed':
        return [...base, 'bg-green-600', 'text-white']
      case 'en cours':
      case 'in_progress':
        return [...base, 'text-white']
      case 'prévu':
      case 'prevu':
      case 'planned':
        return [...base, 'bg-white', 'text-gray-900', 'border', 'border-gray-300']
      case 'en retard':
      case 'retard':
      case 'late':
        return [...base, 'bg-red-600', 'text-white']
      case 'annulé':
      case 'annule':
      case 'cancelled':
        return [...base, 'bg-gray-500', 'text-white']
      default:
        return [...base, 'bg-white', 'text-gray-900', 'border', 'border-gray-300']
    }
  },
  eventDidMount: (arg: any) => {
    const status = (arg.event.extendedProps?.status || '').toLowerCase()
    if (status === 'en cours' || status === 'in_progress') {
      arg.el.style.backgroundColor = '#9CBFA2'
      arg.el.style.borderColor = '#9CBFA2'
    }
  },
  dayCellClassNames: ['cursor-pointer', 'hover:bg-gray-50', 'rounded-lg', 'border-0']
})

const calendarRef = ref<any>(null)

const applyResponsiveOptions = () => {
  const isMobile = window.matchMedia('(max-width: 640px)').matches
  const api = calendarRef.value?.getApi?.()
  
  if (isMobile) {
    api?.changeView('listWeek')
    calendarOptions.value.headerToolbar = {
      left: 'prev,next',
      center: 'title',
      right: 'listWeek'
    }
    ;(calendarOptions.value as any).aspectRatio = 0.9
  } else {
    api?.changeView('dayGridMonth')
    calendarOptions.value.headerToolbar = {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,dayGridWeek,dayGridDay,listWeek'
    }
    ;(calendarOptions.value as any).aspectRatio = 1.35
  }
}

onMounted(() => {
  applyResponsiveOptions()
  const handler = () => applyResponsiveOptions()
  window.addEventListener('resize', handler)
  onUnmounted(() => window.removeEventListener('resize', handler))
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
    <FullCalendar ref="calendarRef" :options="calendarOptions" />
  </div>
</template>

<style scoped>
.calendar-component {
  max-width: 100%;
  margin: 0 auto;
}

@media (max-width: 640px) {
  .calendar-component {
    padding: 0 8px;
  }
}

:deep(.fc-button-primary) {
  background-color: transparent !important;
  border-color: #9CBFA2 !important;
  color: #9CBFA2 !important;
}

:deep(.fc-button-primary:not(:disabled).fc-button-active),
:deep(.fc-button-primary:not(:disabled):active) {
  background-color: rgba(156, 191, 162, 0.1) !important;
  border-color: #9CBFA2 !important;
  color: #9CBFA2 !important;
}

:deep(.fc-button-primary:hover:not(:disabled)) {
  background-color: rgba(156, 191, 162, 0.1) !important;
  border-color: #9CBFA2 !important;
  color: #9CBFA2 !important;
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
