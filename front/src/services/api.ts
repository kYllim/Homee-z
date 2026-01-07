import axios, { AxiosError } from 'axios'
import { GetCookie } from './cookie'

export const API_BASE: string = (import.meta.env.VITE_API_URL as string) || 'http://localhost:8000/'
export const SHOPPING_API_BASE: string = (import.meta.env.VITE_SHOPPING_API_URL as string) || API_BASE

export function joinBase(base: string, path: string): string {
  const b = base.replace(/\/+$/, '')
  const p = path.replace(/^\/+/, '')
  return `${b}/${p}`
}

// Instance Axios commune pour l'API backend
const api = axios.create({
  baseURL: API_BASE,
})

// Intercepteur requête: injecte automatiquement le token depuis les cookies
api.interceptors.request.use((config) => {
  const token = GetCookie('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Intercepteur réponse: log concis des 401
api.interceptors.response.use(
  (response) => response,
  (error: AxiosError) => {
    if (error.response?.status === 401) {
      console.warn('401: non authentifié. Vérifiez votre session/token.')
    }
    return Promise.reject(error)
  }
)

export default api
