<script setup lang="ts">
import { ref, watch } from 'vue'
import type { PropType } from 'vue'
import type { Event } from '@/models/Events.interface'

// Définition des props avec PropType pour les fonctions
const props = defineProps({
  onSubmit: {
    type: Function as PropType<(data: Partial<Event>) => void>,
    required: true
  },
  selectedEvent: {
    type: Object as PropType<Event | null>,
    default: null
  },
  onCancel: {
    type: Function as PropType<() => void>
  }
})

// Champs du formulaire
const title = ref('')
const description = ref('')
const startAt = ref('')
const endAt = ref('')
const type = ref('')
const status = ref('')

// Pré-remplir le formulaire si selectedEvent change
watch(
  () => props.selectedEvent,
  (event) => {
    if (event) {
      title.value = event.title
      description.value = event.description || ''
      startAt.value = event.startAt
      endAt.value = event.endAt || ''
      type.value = event.type || ''
      status.value = event.status || ''
    } else {
      title.value = ''
      description.value = ''
      startAt.value = ''
      endAt.value = ''
      type.value = ''
      status.value = ''
    }
  },
  { immediate: true }
)

// Fonction submit (ajout ou édition)
const submit = () => {
  if (!title.value || !startAt.value) return

  props.onSubmit({
    title: title.value,
    description: description.value,
    startAt: startAt.value,
    endAt: endAt.value,
    type: type.value,
    status: status.value
  })

  // Réinitialiser si on ajoute un nouvel événement
  if (!props.selectedEvent) {
    title.value = ''
    description.value = ''
    startAt.value = ''
    endAt.value = ''
    type.value = ''
    status.value = ''
  }
}
</script>

<template>
  <div class="event-form">
    <input v-model="title" placeholder="Titre de l'événement" />
    <input v-model="description" placeholder="Description" />
    <input v-model="startAt" type="datetime-local" />
    <input v-model="endAt" type="datetime-local" />
    <input v-model="type" placeholder="Type" />
    <input v-model="status" placeholder="Statut" />

    <button @click="submit">{{ props.selectedEvent ? 'Modifier' : 'Ajouter' }}</button>
    <button v-if="props.selectedEvent" @click="props.onCancel">Annuler</button>
  </div>
</template>

<style scoped>
.event-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
}
.event-form input {
  padding: 5px;
  font-size: 14px;
}
.event-form button {
  width: 150px;
  padding: 5px;
  cursor: pointer;
}
</style>
