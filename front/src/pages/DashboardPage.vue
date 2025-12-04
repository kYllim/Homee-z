<template>
  <div>
    <h1>Dashboard</h1>
    <p>Voici le calendrier :</p>

    <!-- On passe les vrais events du store -->
    <Calendar :events="calendarEvents" />
  </div>
</template>

<script setup lang="ts">
import { onMounted, computed } from 'vue'
import Calendar from '@/components/Calendar.vue'
import { useEventStore } from '@/stores/eventStore'

// Récupération du store
const eventStore = useEventStore()

// On monte le store pour charger les events
onMounted(() => {
  eventStore.fetchEvents()
})

// On transforme les events du backend → EventInput pour le calendrier
const calendarEvents = computed(() =>
  eventStore.events.map(e => ({
    id: String(e.id),          // important : FullCalendar veut un string
    title: e.title,
    start: e.startAt,
    end: e.endAt
  }))
)
</script>
