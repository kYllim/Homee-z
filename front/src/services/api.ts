export const API_BASE: string = (import.meta.env.VITE_API_URL as string) || 'http://localhost:8000'
export const SHOPPING_API_BASE: string = (import.meta.env.VITE_SHOPPING_API_URL as string) || API_BASE

// Helper to ensure no double slashes when concatenating
export function joinBase(base: string, path: string) {
  return `${base.replace(/\/$/, '')}/${path.replace(/^\//, '')}`
}
