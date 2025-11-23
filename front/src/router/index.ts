import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'

const HomePage = () => import('../pages/HomePage.vue')
const DashboardPage = () => import('../pages/DashboardPage.vue')

const routes: Array<RouteRecordRaw> = [
  { path: '/', name: 'Home', component: HomePage },
  { path: '/dashboard', name: 'Dashboard', component: DashboardPage },
  { path: '/events', name: 'Events', component: () => import('../pages/EventPage.vue') }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default router
