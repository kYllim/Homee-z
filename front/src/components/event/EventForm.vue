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
    } else {
      title.value = ''
      description.value = ''
      startAt.value = ''
      endAt.value = ''
      type.value = ''
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
  })

  // Réinitialiser si on ajoute un nouvel événement
  if (!props.selectedEvent) {
    title.value = ''
    description.value = ''
    startAt.value = ''
    endAt.value = ''
    type.value = ''
  }
}
</script>

<template>
  <div class="event-form">
    <div class="form-group">
      <label>Titre *</label>
      <input v-model="title" placeholder="Nom de l'événement" required />
    </div>
    
    <div class="form-group">
      <label>Description</label>
      <textarea v-model="description" placeholder="Détails de l'événement" rows="3"></textarea>
    </div>
    
    <div class="form-row">
      <div class="form-group">
        <label>Début *</label>
        <input v-model="startAt" type="datetime-local" required />
      </div>
      
      <div class="form-group">
        <label>Fin</label>
        <input v-model="endAt" type="datetime-local" />
      </div>
    </div>
    
    <div class="form-group">
      <label>Type</label>
      <input v-model="type" placeholder="Ex: réunion, anniversaire..." />
    </div>

    <div class="form-actions">
      <button class="btn-primary" @click="submit">
        {{ props.selectedEvent ? '✏️ Modifier l\'événement' : '➕ Créer l\'événement' }}
      </button>
      <button v-if="props.selectedEvent" class="btn-secondary" @click="props.onCancel">
        ❌ Annuler
      </button>
    </div>
  </div>
</template>

<style scoped>
.event-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.form-group label {
  font-weight: 600;
  color: #2c3e50;
  font-size: 14px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.event-form input,
.event-form textarea {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 14px;
  font-family: inherit;
}

.event-form input:focus,
.event-form textarea:focus {
  outline: none;
  border-color: #3788d8;
  box-shadow: 0 0 0 3px rgba(55, 136, 216, 0.1);
}

.form-actions {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.btn-primary {
  background: #3788d8;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 15px;
  font-weight: 600;
  transition: background 0.2s;
}

.btn-primary:hover {
  background: #2c6bb3;
}

.btn-secondary {
  background: #6c757d;
  color: white;
  border: none;
  padding: 12px 24px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 15px;
  font-weight: 600;
  transition: background 0.2s;
}

.btn-secondary:hover {
  background: #5a6268;
}
</style>
