import axios from 'axios'
import type { Event } from '../models/Events.interface'

const API_URL = 'http://localhost:8000/api/events'


/**
 * Récupérer tous les événements
 */
export async function getEvents(): Promise<Event[]> {
  const res = await axios.get(API_URL)
    console.log('Réponse GET /api/events', res.data) // <- ici

    const members = res.data['hydra:member'] ?? res.data['member'] ?? [] // fallback

  console.log('res.data', res.data)
  return members.map((e: any) => ({
    id: e.id,
    title: e.title,
    description: e.description,
    createdAt: e.createdAt,
    startAt: e.startAt,
    endAt: e.endAt,
    type: e.type,
  }))
}

/**
 * Récupérer un événement par son ID
 */
export async function getEventById(id: number): Promise<Event> {
  const res = await axios.get(`${API_URL}/${id}`)
  console.log('res.data', res.data)
  const e = res.data
  return {
    id: e.id,
    title: e.title,
    description: e.description,
    createdAt: e.createdAt,
    startAt: e.startAt,
    endAt: e.endAt,
    type: e.type,
    status: e.status,
    householdId: e.household?.id ?? 0,
    creatorId: e.creator?.id ?? 0
  }
}

/**
 * Créer un nouvel événement
 */
export async function createEvent(eventData: Partial<Event>): Promise<Event> {
  const res = await axios.post(API_URL, eventData, {
    headers: { 'Content-Type': 'application/ld+json' }
  })
    console.log('res.data', res.data)

  const e = res.data
  return {
    id: e.id,
    title: e.title,
    description: e.description,
    createdAt: e.createdAt,
    startAt: e.startAt,
    endAt: e.endAt,
    type: e.type,
    status: e.status,
    householdId: e.household?.id ?? 0,
    creatorId: e.creator?.id ?? 0
  }
}

/**
 * Modifier un événement existant (PATCH)
 */
export async function updateEvent(id: number, eventData: Partial<Event>): Promise<Event> {
  const res = await axios.patch(`${API_URL}/${id}`, eventData, {
    headers: { 'Content-Type': 'application/merge-patch+json' }
  })
  console.log('res.data', res.data)
  const e = res.data
  return {
    id: e.id,
    title: e.title,
    description: e.description,
    createdAt: e.createdAt,
    startAt: e.startAt,
    endAt: e.endAt,
    type: e.type,
    status: e.status,
    householdId: e.household?.id ?? 0,
    creatorId: e.creator?.id ?? 0
  }
}

/**
 * Supprimer un événement
 */
export async function deleteEvent(id: number): Promise<void> {
  const res = await axios.delete(`${API_URL}/${id}`)
  console.log('res.data', res.data)   
}
