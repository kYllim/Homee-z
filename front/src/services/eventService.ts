import api, { API_BASE, joinBase } from './api'
import type { Event } from '../models/Events.interface'
import { GetCookie } from './index'

const API_URL = joinBase(API_BASE, '/api/events')

const getAuthHeaders = (contentType: string = 'application/json') => ({
  'Content-Type': contentType
})

export async function getEvents(): Promise<Event[]> {
  const res = await api.get(API_URL, {
    headers: getAuthHeaders()
  })
    const members = res.data['hydra:member'] ?? res.data['member'] ?? [] 

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
  const res = await api.get(joinBase(API_URL, `/${id}`), {
    headers: getAuthHeaders()
  })

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

const computeStatusFromDates = (startAt?: string, endAt?: string): string | undefined => {
  if (!startAt) return 'prévu'
  const now = new Date()
  const startDate = new Date(startAt)
  const endDate = endAt ? new Date(endAt) : undefined
  if (startDate > now) return 'prévu'
  if (endDate && endDate < now) return 'en retard'
  return 'en cours'
}

export async function createEvent(eventData: Partial<Event>): Promise<Event> {
  const cleanData = normalizeDates({
    ...eventData,
    status: eventData.status ?? computeStatusFromDates(eventData.startAt, eventData.endAt)
  })
  const res = await api.post(API_URL, cleanData, {
    headers: getAuthHeaders('application/ld+json')
  })

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

export async function updateEvent(id: number, eventData: Partial<Event>): Promise<Event> {
  const cleanData = normalizeDates(eventData)
  console.log('updateEvent: données envoyées:', cleanData)
  const res = await api.patch(joinBase(API_URL, `/${id}`), cleanData, {
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
  const res = await api.delete(joinBase(API_URL, `/${id}`), {
    headers: getAuthHeaders()
  })
}
