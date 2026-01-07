<script setup lang="ts">
import { ref, watch } from 'vue'
import type { Chore } from '@/models/Chore.interface'
import { TYPE_MAPPING } from '@/models/Chore.interface'

const props = defineProps<{
  isOpen: boolean
  chore?: Chore | null
}>()

const emit = defineEmits<{
  close: []
  submit: [choreData: Partial<Chore>]
}>()

const title = ref('')
const description = ref('')
const startDate = ref('')
const endDate = ref('')
const type = ref('cleaning')

// Fonction pour réinitialiser le formulaire (doit être déclarée AVANT le watch)
const resetForm = () => {
  title.value = ''
  description.value = ''
  startDate.value = ''
  endDate.value = ''
  type.value = 'cleaning'
}

// Pré-remplir le formulaire si on édite une corvée
watch(() => props.chore, (chore) => {
  if (chore) {
    title.value = chore.title
    description.value = chore.description
    startDate.value = chore.startDate
    endDate.value = chore.endDate
    type.value = chore.type
  } else {
    resetForm()
  }
}, { immediate: true })

const handleSubmit = () => {
  if (!title.value || !startDate.value || !endDate.value) {
    alert('Veuillez remplir tous les champs obligatoires')
    return
  }

  emit('submit', {
    title: title.value,
    description: description.value,
    startDate: startDate.value,
    endDate: endDate.value,
    type: type.value
  })

  if (!props.chore) resetForm()
}

const handleClose = () => {
  resetForm()
  emit('close')
}
</script>

<template>
  <div v-if="isOpen" class="modal-overlay" @click.self="handleClose">
    <div class="modal-content">
      <!-- Header -->
      <div class="modal-header">
        <h3 class="text-2xl font-['Outfit'] font-bold text-[#333333]">
          {{ chore ? 'Modifier la corvée' : 'Nouvelle corvée' }}
        </h3>
        <button @click="handleClose" class="text-gray-500 hover:text-[#333333]">
          <i class="fa-solid fa-times text-xl"></i>
        </button>
      </div>

      <!-- Form -->
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label">Titre *</label>
          <input 
            v-model="title"
            type="text"
            class="form-input"
            placeholder="Ex: Passer l'aspirateur"
          />
        </div>

        <div class="form-group">
          <label class="form-label">Description *</label>
          <textarea 
            v-model="description"
            class="form-input"
            rows="3"
            placeholder="Décrivez la corvée..."
          ></textarea>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Date de début *</label>
            <input 
              v-model="startDate"
              type="date"
              class="form-input"
            />
          </div>

          <div class="form-group">
            <label class="form-label">Date de fin *</label>
            <input 
              v-model="endDate"
              type="date"
              class="form-input"
            />
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Type de corvée *</label>
          <select v-model="type" class="form-input">
            <option
              v-for="[key, config] in Object.entries(TYPE_MAPPING)"
              :key="key"
              :value="config.key"
            >
              {{ config.label }}
            </option>
          </select>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button @click="handleClose" class="btn-secondary">
          Annuler
        </button>
        <button @click="handleSubmit" class="btn-primary">
          {{ chore ? 'Modifier' : 'Créer' }}
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 1rem;
}

@media (max-width: 639px) {
  .modal-overlay {
    padding: 0.5rem;
    align-items: flex-start;
    padding-top: 2rem;
  }
}

.modal-content {
  background: white;
  border-radius: 1rem;
  max-width: 600px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #e5e7eb;
}

@media (min-width: 640px) {
  .modal-header {
    padding: 1.5rem;
  }
}

.modal-body {
  padding: 1rem;
}

@media (min-width: 640px) {
  .modal-body {
    padding: 1.5rem;
  }
}

.modal-footer {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  padding: 1rem;
  border-top: 1px solid #e5e7eb;
}

@media (min-width: 640px) {
  .modal-footer {
    flex-direction: row;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.5rem;
  }
}

.form-group {
  margin-bottom: 1.25rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}

@media (min-width: 640px) {
  .form-row {
    grid-template-columns: 1fr 1fr;
  }
}

.form-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #333333;
  margin-bottom: 0.5rem;
}

.form-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  transition: all 0.2s;
}

.form-input:focus {
  outline: none;
  border-color: #9CBFA2;
  range: 2px;
  outline-color: rgba(156, 191, 162, 0.2);
}

.btn-primary {
  background: #9CBFA2;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  width: 100%;
}

@media (min-width: 640px) {
  .btn-primary {
    width: auto;
  }
}

.btn-primary:hover {
  background: #8aad90;
}

.btn-secondary {
  background: white;
  color: #6b7280;
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  font-weight: 600;
  border: 1px solid #d1d5db;
  cursor: pointer;
  transition: all 0.2s;
  width: 100%;
}

@media (min-width: 640px) {
  .btn-secondary {
    width: auto;
  }
}

.btn-secondary:hover {
  background: #f9fafb;
}
</style>