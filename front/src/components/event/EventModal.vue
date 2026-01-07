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
    <div class="bg-white w-[95vw] sm:w-full max-w-[95vw] sm:max-w-xl max-h-[90vh] overflow-y-auto rounded-3xl shadow-xl p-4 sm:p-8 relative">
      <button
        @click="emit('close')"
        class="absolute top-3 right-3 h-7 w-7 sm:h-8 sm:w-8 flex justify-center items-center bg-gray-100 hover:bg-gray-200 rounded-full text-black"
        aria-label="Fermer"
      >
        ×
      </button>

      <div class="mb-6">
        <h2 v-if="currentMode === 'view'" class="text-xl sm:text-2xl font-extrabold text-gray-800">Détails de l'événement</h2>
        <h2 v-else-if="currentMode === 'create'" class="text-xl sm:text-2xl font-extrabold text-gray-800">Créer un événement</h2>
        <h2 v-else-if="currentMode === 'edit'" class="text-xl sm:text-2xl font-extrabold text-gray-800">Modifier l'événement</h2>
        <h2 v-else class="text-xl sm:text-2xl font-extrabold text-gray-800">Supprimer l'événement</h2>
      </div>

      <div v-if="currentMode === 'view'" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Titre</label>
          <p class="text-sm text-gray-700 p-3 bg-gray-100 rounded-lg border-l-4 border-green_pastel">{{ eventData?.title || 'Sans titre' }}</p>
        </div>

        <div v-if="eventData?.description">
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Description</label>
          <p class="text-sm text-gray-700 p-3 bg-gray-100 rounded-lg border-l-4 border-green_pastel">{{ eventData.description }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Début</label>
            <p class="text-sm text-gray-700 p-3 bg-gray-100 rounded-lg border-l-4 border-green_pastel">{{ formatDate(eventData?.startAt || '') }}</p>
          </div>

          <div v-if="eventData?.endAt">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Fin</label>
            <p class="text-sm text-gray-700 p-3 bg-gray-100 rounded-lg border-l-4 border-green_pastel">{{ formatDate(eventData.endAt) }}</p>
          </div>
        </div>

        <div v-if="eventData?.type">
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Type</label>
          <p class="text-sm text-gray-700 p-3 bg-gray-100 rounded-lg border-l-4 border-green_pastel">{{ eventData.type }}</p>
        </div>

        <div>
          <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Statut</label>
          <span class="inline-block px-3 py-1.5 rounded-full text-xs font-semibold" :class="getStatusClass(getComputedStatus())">
            {{ getComputedStatus() }}
          </span>
        </div>

        <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
          <button class="flex-1 py-3 rounded-xl bg-green_pastel hover:bg-opacity-90 text-white font-semibold transition" @click="switchToEdit">
            Modifier
          </button>
          <button v-if="getComputedStatus() !== 'terminé'" class="py-3 px-4 rounded-xl bg-green_pastel hover:bg-opacity-90 text-white font-semibold transition" @click="markAsCompleted">
            Marquer comme terminé
          </button>
          <button class="py-3 px-4 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold transition" @click="switchToDelete">
            Supprimer
          </button>
        </div>
      </div>

      <div v-else-if="currentMode !== 'delete'" class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Titre *</label>
          <input v-model="title" placeholder="Nom de l'événement" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel focus:border-transparent text-black" />
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
          <textarea v-model="description" placeholder="Détails de l'événement" rows="3" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel focus:border-transparent text-black resize-vertical"></textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Début *</label>
            <input v-model="startAt" type="datetime-local" required class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel focus:border-transparent text-black" />
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Fin</label>
            <input v-model="endAt" type="datetime-local" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel focus:border-transparent text-black" />
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
          <input v-model="type" placeholder="Ex: réunion, anniversaire..." class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel focus:border-transparent text-black" />
        </div>

        <div v-if="currentMode === 'edit'">
          <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
          <select v-model="status" class="w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green_pastel focus:border-transparent text-black bg-white">
            <option value="">Auto (selon les dates)</option>
            <option value="prévu">Prévu</option>
            <option value="en cours">En cours</option>
            <option value="en retard">En retard</option>
            <option value="terminé">Terminé</option>
            <option value="annulé">Annulé</option>
          </select>
        </div>

        <div class="flex justify-between gap-3 mt-6 pt-4 border-t border-gray-200">
          <button @click="emit('close')" class="flex-1 py-3 px-4 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl font-semibold transition">
            Annuler
          </button>
          <button @click="handleSave" class="flex-1 py-3 px-4 bg-green_pastel hover:bg-opacity-90 text-white rounded-xl font-semibold transition">
            {{ currentMode === 'create' ? 'Créer' : 'Enregistrer' }}
          </button>
        </div>
      </div>

      <div v-else class="space-y-4">
        <p class="text-base text-gray-800">Voulez-vous vraiment supprimer cet événement ?</p>
        <p class="text-base font-semibold text-gray-900 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded"><strong>{{ eventData?.title }}</strong></p>
        
        <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
          <button @click="emit('close')" class="flex-1 py-3 px-4 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl font-semibold transition">
            Annuler
          </button>
          <button @click="handleDelete" class="flex-1 py-3 px-4 bg-red-500 hover:bg-red-600 text-white rounded-xl font-semibold transition">
            Oui, supprimer
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.status-prévu,
.status-planned {
  background: #ffffff !important;
  color: #1f2937 !important;
  border: 1px solid #d1d5db !important;
}

.status-terminé,
.status-completed {
  background: #16a34a !important;
  color: #ffffff;
}

.status-annule,
.status-annulé,
.status-cancelled {
  background: #6b7280 !important;
  color: #ffffff;
}

.status-en_cours,
.status-in_progress {
  background: #9CBFA2 !important;
  color: #ffffff;
}

.status-en_retard,
.status-retard,
.status-late {
  background: #dc2626 !important;
  color: #ffffff;
}
</style>

