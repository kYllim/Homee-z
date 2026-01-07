import axios from 'axios'
import type { Event } from '../models/Events.interface'
import { GetCookie } from './index'
import { API_BASE } from './api'

const API_URL = `${API_BASE}/api/events`

const getAuthHeaders = (contentType: string = 'application/json') => {
  const token = GetCookie('token')
  console.log('Token JWT récupéré:', token)
  return {
    'Authorization': `Bearer ${token}`,
    'Content-Type': contentType
  }
}

export async function getEvents(): Promise<Event[]> {
  const res = await axios.get(API_URL, {
    headers: getAuthHeaders()
  })
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
    status: e.status,
    householdId: e.household?.id ?? 0,
    creatorId: e.creator?.id ?? 0
  }))
}

export async function getEventById(id: number): Promise<Event> {
  const res = await axios.get(`${API_URL}/${id}`, {
    headers: getAuthHeaders()
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

export async function createEvent(eventData: Partial<Event>): Promise<Event> {
  const cleanData = normalizeDates(eventData)
  const res = await axios.post(API_URL, cleanData, {
    headers: getAuthHeaders('application/ld+json')
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

const normalizeDates = (data: any) => {
  const normalized = { ...data }
  if (normalized.startAt) {
    normalized.startAt = normalized.startAt.split('+')[0].split('Z')[0]
  }
  if (normalized.endAt) {
    normalized.endAt = normalized.endAt.split('+')[0].split('Z')[0]
  }
  return normalized
}

export async function updateEvent(id: number, eventData: Partial<Event>): Promise<Event> {
  const cleanData = normalizeDates(eventData)
  console.log('updateEvent: données envoyées:', cleanData)
  const res = await axios.patch(`${API_URL}/${id}`, cleanData, {
    headers: getAuthHeaders('application/merge-patch+json')
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

export async function deleteEvent(id: number): Promise<void> {
  const res = await axios.delete(`${API_URL}/${id}`, {
    headers: getAuthHeaders()
  })
  console.log('res.data', res.data)   
}
