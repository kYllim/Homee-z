import axios from "axios"

function getCookie(name: string): string | null {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) return parts.pop()!.split(";").shift()!
  return null
}

const api = axios.create({
  baseURL: "http://127.0.0.1:8000/api",
})

api.interceptors.request.use((config) => {
  const token = getCookie("token")

  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

export default api
