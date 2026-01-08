import type { Chore } from '../models/Chore.interface'
import { TYPE_MAPPING, STATUS_MAPPING, STATUS_REVERSE_MAPPING } from '../models/Chore.interface'
import { GetCookie } from './cookie'

const API_URL = 'http://localhost:8000/api/events'

/**
 * Récupérer le token d'authentification
 */
function getAuthToken(): string | null {
  return GetCookie('token')
}

/**
 * Mapper Event backend → Chore frontend
 */
function mapEventToChore(e: any, currentUserId?: number): Chore {
  // Récupérer le type mapping avec fallback
  const typeKey = e.type?.toLowerCase() || 'menage'
  const typeConfig = TYPE_MAPPING[typeKey] ?? { key: 'cleaning', label: 'Ménage', icon: 'fa-broom' }
  
  // Récupérer le status mapping avec fallback
  const statusKey = e.status?.toLowerCase() || 'prévu'
  const status = STATUS_MAPPING[statusKey] ?? 'todo'
  
  return {
    id: e.id,
    title: e.title,
    description: e.description,
    createdAt: e.createdAt,
    startDate: e.startAt,
    endDate: e.endAt,
    type: typeConfig.key,
    category: typeConfig.label,
    status: status,
    householdId: e.household?.id ?? 0,
    creatorId: e.creator?.id ?? 0,
    assignee: e.creator ? {
      id: e.creator.id,
      name: `${e.creator.firstName || ''} ${e.creator.lastName || ''}`.trim() || 'Utilisateur',
      avatar: e.creator.avatar || 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-5.jpg'
    } : undefined,
    isAssignedToMe: currentUserId ? e.creator?.id === currentUserId : false
  }
}

/**
 * Mapper Chore frontend → Event backend (pour création/modification)
 */
function mapChoreToEvent(chore: Partial<Chore>): any {
  // Reverse mapping du type
  const typeLabel = Object.entries(TYPE_MAPPING).find(
    ([, config]) => config.key === chore.type
  )?.[0] || 'menage'
  
  // Reverse mapping du status
  const statusLabel = chore.status ? STATUS_REVERSE_MAPPING[chore.status] : 'prévu'
  
  // Convertir date (YYYY-MM-DD) en datetime (YYYY-MM-DDTHH:MM:SS)
  const formatDateTime = (date?: string) => {
    if (!date) return undefined
    if (date.includes('T')) return date
    return `${date}T12:00:00`
  }
  
  return {
    title: chore.title,
    description: chore.description,
    startAt: formatDateTime(chore.startDate),
    endAt: formatDateTime(chore.endDate),
    type: typeLabel,
    status: statusLabel
  }
}

export async function getChores(currentUserId?: number): Promise<Chore[]> {
  const response = await fetch(API_URL, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      ...(getAuthToken() ? { Authorization: `Bearer ${getAuthToken()}` } : {})
    }
  })
  
  if (!response.ok) {
    throw new Error(`HTTP error ${response.status}`)
  }
  
  const data = await response.json()
  const members = data['hydra:member'] ?? data['member'] ?? []
  return members.map((e: any) => mapEventToChore(e, currentUserId))
}

/**
 * Récupérer une corvée par son ID
 */
export async function getChoreById(id: number, currentUserId?: number): Promise<Chore> {
  const response = await fetch(`${API_URL}/${id}`, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      ...(getAuthToken() ? { Authorization: `Bearer ${getAuthToken()}` } : {})
    }
  })
  
  if (!response.ok) {
    throw new Error(`HTTP error ${response.status}`)
  }
  
  const data = await response.json()
  return mapEventToChore(data, currentUserId)
}

/**
 * Créer une nouvelle corvée
 */
export async function createChore(choreData: Partial<Chore>): Promise<Chore> {
  const eventData = mapChoreToEvent(choreData)
  console.log('Données envoyées au backend:', eventData)
  console.log(getAuthToken());
  
  const response = await fetch(API_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/ld+json',
      ...(getAuthToken() ? { Authorization: `Bearer ${getAuthToken()}` } : {})
    },
    body: JSON.stringify(eventData)
  })

  
  
  if (!response.ok) {
    const error = await response.json()
    console.error('Erreur backend:', error)
    throw new Error(error.message || `HTTP error ${response.status}`)
  }
  
  const data = await response.json()
  console.log('Réponse backend:', data)
  return mapEventToChore(data)
}

/**
 * Modifier une corvée existante (PATCH)
 */
export async function updateChore(id: number, choreData: Partial<Chore>): Promise<Chore> {
  const eventData = mapChoreToEvent(choreData)
  
  const response = await fetch(`${API_URL}/${id}`, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/merge-patch+json',
      ...(getAuthToken() ? { Authorization: `Bearer ${getAuthToken()}` } : {})
    },
    body: JSON.stringify(eventData)
  })
  
  if (!response.ok) {
    throw new Error(`HTTP error ${response.status}`)
  }
  
  const data = await response.json()
  return mapEventToChore(data)
}

/**
 * Supprimer une corvée
 */
export async function deleteChore(id: number): Promise<void> {
  const response = await fetch(`${API_URL}/${id}`, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      ...(getAuthToken() ? { Authorization: `Bearer ${getAuthToken()}` } : {})
    }
  })
  
  if (!response.ok) {
    throw new Error(`HTTP error ${response.status}`)
  }
}
