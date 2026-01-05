export interface Chore {
  id: number
  title: string
  description: string
  createdAt: string      // ISO string
  startDate: string      // ISO string (sera mappé depuis startAt)
  endDate: string        // ISO string (sera mappé depuis endAt)
  type: string           // cleaning, cooking, laundry, shopping, garden
  category: string       // Ménage, Cuisine, Linge, Courses, Jardin
  status: string         // todo, in-progress, done, overdue
  householdId: number
  creatorId: number
  assignee?: {
    id: number
    name: string
    avatar: string
  }
  isAssignedToMe?: boolean
}

// Mapping des types Event → Chore
export const TYPE_MAPPING: Record<string, { key: string; label: string; icon: string }> = {
  'menage': { key: 'cleaning', label: 'Ménage', icon: 'fa-broom' },
  'cuisine': { key: 'cooking', label: 'Cuisine', icon: 'fa-utensils' },
  'linge': { key: 'laundry', label: 'Linge', icon: 'fa-tshirt' },
  'courses': { key: 'shopping', label: 'Courses', icon: 'fa-shopping-cart' },
  'jardin': { key: 'garden', label: 'Jardin', icon: 'fa-seedling' }
}

// Mapping des statuts Event → Chore
export const STATUS_MAPPING: Record<string, string> = {
  'à faire': 'todo',
  'en cours': 'in-progress',
  'terminé': 'done',
  'en retard': 'overdue'
}

export const STATUS_REVERSE_MAPPING: Record<string, string> = {
  'todo': 'à faire',
  'in-progress': 'en cours',
  'done': 'terminé',
  'overdue': 'en retard'
}