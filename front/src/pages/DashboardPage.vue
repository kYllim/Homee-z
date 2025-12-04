<template>
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
</template>

<script setup>
import NavBarConnect from "@/components/NavBarConnect.vue";
import Calendar from '@/components/Calendar.vue'
import ToDoList from "@/components/ToDoList.vue";
</script>
