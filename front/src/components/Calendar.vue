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
    emit('eventClick', arg)
  },
  eventClassNames: ['bg-primary', 'text-white', 'rounded-md', 'px-1', 'py-0.5', 'border-0'],
  dayCellClassNames: ['cursor-pointer', 'hover:bg-gray-50', 'rounded-lg', 'border-0']
})

const calendarRef = ref<any>(null)
let resizeHandler: (() => void) | null = null

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
  resizeHandler = () => applyResponsiveOptions()
  window.addEventListener('resize', resizeHandler)
})

onUnmounted(() => {
  if (resizeHandler) {
    window.removeEventListener('resize', resizeHandler)
  }
})

watch(
  () => props.events,
  (newEvents) => {
    if (calendarOptions.value) {
      calendarOptions.value.events = newEvents
      const api = calendarRef.value?.getApi?.()
      if (api) {
        api.refetchEvents()
      }
    }
  },
  { deep: true }
)
</script>

<template>
  <div class="calendar-component">
    <FullCalendar :options="calendarOptions" />
  </div>
</template>

<style scoped>
.calendar-component {
  max-width: 100%;
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
