export interface Event {
  id: number
  title: string
  description: string
  createdAt: string      // ISO string
  startAt: string        // ISO string
  endAt: string          // ISO string
  type: string
  status: string
  householdId: number
  creatorId: number
}
