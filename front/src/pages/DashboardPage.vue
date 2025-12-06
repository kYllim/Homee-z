<script setup lang="ts">
  import { onMounted, computed } from 'vue'
  import Calendar from '@/components/Calendar.vue'
  import { useEventStore } from '@/stores/eventStore'
  import NavBarConnect from "@/components/NavBarConnect.vue";
  import ToDoList from "@/components/ToDoList.vue";

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
              <h2 class="text-lg font-semibold text-gray-800">Reminders</h2>
            </div>
          </div>
        </div>
        <div class="bg-white shadow-sm rounded-2xl p-6 mt-8">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Schedule</h2>
          <Calendar />
        </div>
      </div>
    </div>
    <div>
      <h1>Dashboard</h1>
      <p>Voici le calendrier :</p>

      <!-- On passe les vrais events du store -->
      <Calendar :events="calendarEvents" />
    </div>
  </div>
</template>


