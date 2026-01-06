<script setup lang="ts">
import { ref, watch, onMounted, onUnmounted } from 'vue'
import type { Event } from '@/models/Events.interface'

const props = defineProps<{
  mode: 'view' | 'create' | 'edit' | 'delete',
  eventData?: Event | null
}>()

const emit = defineEmits(['close', 'save', 'delete'])

const currentMode = ref(props.mode)

const title = ref('')
const description = ref('')
const startAt = ref('')
const endAt = ref('')
const type = ref('')
const status = ref('')

const formatDateForInput = (dateStr?: string): string => {
  if (!dateStr) return ''
  const base = dateStr.split('+')[0] ?? ''
  return base.split('Z')[0] ?? ''
}

const statusRefreshTrigger = ref(0)

onMounted(() => {
  const intervalId = setInterval(() => {
    statusRefreshTrigger.value++
  }, 10000)
  
  onUnmounted(() => {
    clearInterval(intervalId)
  })
})

watch(
  () => props.eventData,
  (event) => {
    if (event) {
      title.value = event.title || ''
      description.value = event.description || ''
      startAt.value = formatDateForInput(event.startAt)
      endAt.value = formatDateForInput(event.endAt)
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

const handleSave = () => {
  if (!title.value || !startAt.value) {
    alert('Le titre et la date de début sont obligatoires')
    return
  }

  const eventData: any = {
    title: title.value,
    description: description.value,
    startAt: startAt.value,
    endAt: endAt.value,
    type: type.value,
  }

  if (status.value) {
    eventData.status = status.value
  }

  if (props.eventData?.id) {
    eventData.id = props.eventData.id
  }

  emit('save', eventData)
}

const handleDelete = () => {
  emit('delete', props.eventData)
}

const switchToEdit = () => {
  currentMode.value = 'edit'
}

const switchToDelete = () => {
  currentMode.value = 'delete'
}

const markAsCompleted = () => {
  if (props.eventData?.id) {
    emit('save', {
      id: props.eventData.id,
      status: 'terminé'
    })
  }
}

const formatDate = (dateStr: string) => {
  if (!dateStr) return 'Non définie'
  const date = new Date(dateStr)
  return date.toLocaleString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const getComputedStatus = () => {
  statusRefreshTrigger.value

  if (!props.eventData) return 'prévu'

  if (props.eventData.status && props.eventData.status.trim() !== '') {
    return props.eventData.status
  }

  if (!props.eventData.startAt) return 'prévu'

  const now = new Date()
  const startDate = new Date(props.eventData.startAt)
  const endDate = props.eventData.endAt ? new Date(props.eventData.endAt) : null

  if (startDate > now) return 'prévu'
  if (endDate && endDate < now) return 'en retard'
  return 'en cours'
}

const getStatusClass = (status: string) => {
  return 'status-' + (status || '').toLowerCase().replace(/ /g, '_')
}
</script>

<template>
  <div class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50" @click.self="emit('close')">
    <div class="bg-white w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-3xl shadow-xl p-6 sm:p-8 relative">
      <button
        @click="emit('close')"
        class="absolute top-4 right-4 h-8 w-8 flex justify-center items-center bg-gray-100 hover:bg-gray-200 rounded-full text-black"
        aria-label="Fermer"
      >
        ×
      </button>

      <div class="mb-6">
        <h2 v-if="currentMode === 'view'" class="text-2xl font-extrabold text-gray-800">Détails de l'événement</h2>
        <h2 v-else-if="currentMode === 'create'" class="text-2xl font-extrabold text-gray-800">Créer un événement</h2>
        <h2 v-else-if="currentMode === 'edit'" class="text-2xl font-extrabold text-gray-800">Modifier l'événement</h2>
        <h2 v-else class="text-2xl font-extrabold text-gray-800">Supprimer l'événement</h2>
      </div>

      <div v-if="currentMode === 'view'" class="modal-body">
        <div class="detail-group">
          <label>Titre</label>
          <p class="detail-value">{{ eventData?.title || 'Sans titre' }}</p>
        </div>

        <div class="detail-group" v-if="eventData?.description">
          <label>Description</label>
          <p class="detail-value">{{ eventData.description }}</p>
        </div>

        <div class="detail-row">
          <div class="detail-group">
            <label>Début</label>
            <p class="detail-value">{{ formatDate(eventData?.startAt || '') }}</p>
          </div>

          <div class="detail-group" v-if="eventData?.endAt">
            <label>Fin</label>
            <p class="detail-value">{{ formatDate(eventData.endAt) }}</p>
          </div>
        </div>

        <div class="detail-group" v-if="eventData?.type">
          <label>Type</label>
          <p class="detail-value">{{ eventData.type }}</p>
        </div>

        <div class="detail-group">
          <label>Statut</label>
          <p class="detail-value status-badge" :class="getStatusClass(getComputedStatus())">
            {{ getComputedStatus() }}
          </p>
        </div>

        <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
          <button class="flex-1 py-3 rounded-xl bg-green_pastel hover:bg-green_pastel/90 text-white font-semibold" @click="switchToEdit">
            Modifier
          </button>
          <button v-if="getComputedStatus() !== 'terminé'" class="py-3 px-4 rounded-xl bg-green_pastel hover:bg-green_pastel/90 text-white font-semibold" @click="markAsCompleted">
            Marquer comme terminé
          </button>
          <button class="py-3 px-4 rounded-xl bg-[#dc3545] hover:bg-[#c82333] text-white font-semibold" @click="switchToDelete">
            Supprimer
          </button>
        </div>
      </div>

      <div v-else-if="currentMode !== 'delete'" class="modal-body">
        <div class="form-group">
          <label>Titre *</label>
          <input v-model="title" placeholder="Nom de l'événement" required class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel text-black" />
        </div>
        
        <div class="form-group">
          <label>Description</label>
          <textarea v-model="description" placeholder="Détails de l'événement" rows="3" class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel text-black"></textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="form-group">
            <label>Début *</label>
            <input v-model="startAt" type="datetime-local" required class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel text-black" />
          </div>
          
          <div class="form-group">
            <label>Fin</label>
            <input v-model="endAt" type="datetime-local" class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel text-black" />
          </div>
        </div>
        
        <div class="form-group">
          <label>Type</label>
          <input v-model="type" placeholder="Ex: réunion, anniversaire..." class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel text-black" />
        </div>

        <div class="form-group" v-if="currentMode === 'edit'">
          <label>Statut</label>
          <select v-model="status" class="w-full mt-1 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel text-black bg-white">
            <option value="">Auto (selon les dates)</option>
            <option value="prévu">Prévu</option>
            <option value="en cours">En cours</option>
            <option value="en retard">En retard</option>
            <option value="terminé">Terminé</option>
            <option value="annulé">Annulé</option>
          </select>
        </div>

        <div class="flex justify-between mt-6">
          <button class="w-40 py-3 rounded-xl bg-green_pastel hover:bg-green_pastel/90 text-white font-semibold" @click="handleSave">
            {{ currentMode === 'edit' ? 'Enregistrer' : 'Créer' }}
          </button>
          <button class="w-32 py-3 rounded-xl border border-gray-300 text-gray-600 font-medium" @click="emit('close')">
            Annuler
          </button>
        </div>

        <div v-if="currentMode === 'edit'" class="mt-6 pt-6 border-t border-dashed border-red-400">
          <button class="w-full py-3 rounded-xl bg-[#dc3545] hover:bg-[#c82333] text-white font-semibold" @click="handleDelete">
            Supprimer définitivement
          </button>
        </div>
      </div>

      <div v-else class="modal-body">
        <p class="delete-warning">Voulez-vous vraiment supprimer cet événement ?</p>
        <p class="delete-info"><strong>{{ eventData?.title }}</strong></p>
        
        <div class="flex justify-between mt-6">
          <button class="w-40 py-3 rounded-xl bg-[#dc3545] hover:bg-[#c82333] text-white font-semibold" @click="handleDelete">
            Oui, supprimer
          </button>
          <button class="w-32 py-3 rounded-xl border border-gray-300 text-gray-600 font-medium" @click="emit('close')">
            Annuler
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.modal-content {
  background: white;
  padding: 0;
  border-radius: 12px;
  width: 90%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 25px;
  border-bottom: 1px solid #e9ecef;
  background: #f8f9fa;
  border-radius: 12px 12px 0 0;
}

.modal-header h2 {
  margin: 0;
  color: #2c3e50;
  font-size: 20px;
}

.close-btn {
  background: none;
  border: none;
  font-size: 24px;
  color: #6c757d;
  cursor: pointer;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  transition: all 0.2s;
}

.close-btn:hover {
  background: #e9ecef;
  color: #2c3e50;
}

.modal-body {
  padding: 25px;
}

@media (max-width: 640px) {
  .modal-body {
    padding: 18px;
  }
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-bottom: 20px;
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
  margin-bottom: 20px;
}

input,
textarea {
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  transition: border-color 0.2s, box-shadow 0.2s;
}

select {
  padding: 10px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  transition: border-color 0.2s, box-shadow 0.2s;
  background: white;
  cursor: pointer;
}

input:focus,
textarea:focus,
select:focus {
  outline: none;
  border-color: #9CBFA2;
  box-shadow: 0 0 0 3px rgba(156, 191, 162, 0.25);
}

textarea {
  resize: vertical;
  min-height: 80px;
}

.form-actions {
  display: flex;
  gap: 10px;
  margin-top: 25px;
}

.btn-primary {
  flex: 1;
  background: #3788d8;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
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
  padding: 12px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 15px;
  font-weight: 600;
  transition: background 0.2s;
}

.btn-secondary:hover {
  background: #5a6268;
}

.delete-section {
  margin-top: 25px;
  padding-top: 25px;
  border-top: 2px dashed #dc3545;
}

.btn-delete {
  width: 100%;
  background: #dc3545;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 15px;
  font-weight: 600;
  transition: background 0.2s;
}

.btn-delete:hover {
  background: #c82333;
}

.delete-warning {
  font-size: 16px;
  color: #2c3e50;
  margin-bottom: 15px;
}

.delete-info {
  background: #fff3cd;
  padding: 12px 15px;
  border-radius: 6px;
  border-left: 4px solid #ffc107;
  margin-bottom: 20px;
}

.delete-info strong {
  color: #856404;
}

.detail-group {
  margin-bottom: 20px;
}

.detail-group label {
  font-weight: 600;
  color: #6c757d;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: block;
  margin-bottom: 8px;
}

.detail-value {
  color: #2c3e50;
  font-size: 15px;
  margin: 0;
  padding: 10px 12px;
  background: #f8f9fa;
  border-radius: 6px;
  border-left: 3px solid #9CBFA2;
}

.detail-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 20px;
}

@media (max-width: 640px) {
  .detail-row {
    grid-template-columns: 1fr;
  }
}

.status-badge {
  display: inline-block;
  padding: 6px 12px !important;
  border-radius: 20px;
  font-weight: 600;
  font-size: 13px;
  border: none !important;
}


.status-prévu,
.status-planned {
  background: #ecf4ef !important;
  color: #2f4a35;
}

.status-terminé,
.status-completed {
  background: #d8e8dd !important;
  color: #2f4a35;
}

.status-annule,
.status-annulé,
.status-cancelled {
  background: #4b5563 !important;
  color: #ffffff;
}

.status-en_cours,
.status-in_progress {
  background: #e2f0e7 !important;
  color: #2f4a35;
}

.status-en_retard,
.status-retard,
.status-late {
  background: #f8d7da !important;
  color: #58151c;
}

.view-actions {
  display: flex;
  gap: 10px;
  margin-top: 30px;
  padding-top: 20px;
  border-top: 1px solid #e9ecef;
}

.btn-edit {
  flex: 1;
  background: #3788d8;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 15px;
  font-weight: 600;
  transition: background 0.2s;
}

.btn-edit:hover {
  background: #2c6bb3;
}

.btn-delete-icon {
  background: #dc3545;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 15px;
  font-weight: 600;
  transition: background 0.2s;
}

.btn-delete-icon:hover {
  background: #c82333;
}

.btn-complete {
  background: #28a745;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 15px;
  font-weight: 600;
  transition: background 0.2s;
}

.btn-complete:hover {
  background: #218838;
}
</style>
