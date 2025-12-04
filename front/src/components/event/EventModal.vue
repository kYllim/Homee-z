<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps<{
  mode: 'create' | 'edit' | 'delete',
  eventData?: any
}>()

const emit = defineEmits(['close', 'save', 'delete'])

const localEvent = ref({ ...props.eventData })
</script>

<template>
  <div class="modal-overlay">
    <div class="modal-content">

      <h2 v-if="mode === 'create'">Créer un événement</h2>
      <h2 v-else-if="mode === 'edit'">Modifier l'événement</h2>
      <h2 v-else>Supprimer l'événement ?</h2>

      <!-- Formulaire pour create / edit -->
      <div v-if="mode !== 'delete'">
        <label>Titre :</label>
        <input v-model="localEvent.title" />

        <label>Date début :</label>
        <input type="datetime-local" v-model="localEvent.start" />

        <label>Date fin :</label>
        <input type="datetime-local" v-model="localEvent.end" />

        <label>Description :</label>
        <textarea v-model="localEvent.description" />

        <button @click="emit('save', localEvent)">Enregistrer</button>
      </div>

      <!-- Confirmation delete -->
      <div v-else>
        <p>Voulez-vous vraiment supprimer cet événement ?</p>
        <button @click="emit('delete', props.eventData)">Oui, supprimer</button>
      </div>

      <button @click="emit('close')">Fermer</button>

    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, .5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
}
</style>
