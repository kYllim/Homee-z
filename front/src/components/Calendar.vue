<script setup>
import { ref } from "vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid/index.js";
import interactionPlugin from "@fullcalendar/interaction/index.js";

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: "dayGridMonth",
  headerToolbar: {
    left: "prev,next today",
    center: "title",
    right: "dayGridMonth,dayGridWeek,dayGridDay",
  },
  selectable: true,
  dateClick: (arg) => {
    alert("date click! " + arg.dateStr);
  },
  events: [
    { title: "event 1", date: "2019-04-01" },
    { title: "event 2", date: "2019-04-02" },
  ],
});
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
