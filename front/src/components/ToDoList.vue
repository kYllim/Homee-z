<template>
  <div class="bg-white shadow-sm rounded-2xl p-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold text-gray-800">To-Do List</h2>

      <button 
        @click="showInput = !showInput"
        class="h-8 w-8 rounded-full bg-green-200 flex items-center justify-center text-green-700 font-bold text-xl hover:bg-green-300 transition"
      >
        +
      </button>
    </div>

    <!-- Input for creating task -->
    <div v-if="showInput" class="mb-5">
      <form @submit.prevent="submitTask" class="flex gap-3">
        <input
          v-model="newTask"
          type="text"
          placeholder="Nouvelle tâche..."
          class="flex-1 px-4 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-300"
        />
        <button
          class="bg-green-500 text-white px-4 py-2 rounded-xl font-medium hover:opacity-90 transition"
          type="submit"
        >
          Ajouter
        </button>
      </form>
    </div>

    <!-- Empty message -->
    <div v-if="tasks.length === 0" class="text-gray-400 text-sm italic">
      Aucune tâche pour le moment.
    </div>

    <!-- List -->
    <ul v-else class="space-y-4">
      <li
        v-for="task in tasks"
        :key="task.id"
        class="flex items-center gap-3"
      >
        <!-- Checkbox -->
        <input
          type="checkbox"
          class="h-4 w-4"
          v-model="task.done"
        />

        <!-- Task text -->
        <span
          :class="task.done ? 'text-gray-400 line-through' : 'text-gray-700'"
        >
          {{ task.label }}
        </span>

        <!-- Delete button -->
        <button
          @click="deleteTask(task.id)"
          class="ml-auto text-red-500 hover:text-red-700 text-sm font-medium transition"
        >
          Supprimer
        </button>
      </li>
    </ul>

  </div>
</template>

<script setup>
import { ref } from "vue";

const tasks = ref([]);
const newTask = ref("");
const showInput = ref(false);

const submitTask = () => {
  if (!newTask.value.trim()) return;

  tasks.value.push({
    id: Date.now(),
    label: newTask.value,
    done: false,
  });

  newTask.value = "";
  showInput.value = false;
};

const deleteTask = (id) => {
  tasks.value = tasks.value.filter(task => task.id !== id);
};
</script>
