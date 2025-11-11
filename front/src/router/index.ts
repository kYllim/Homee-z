import { createRouter, createWebHistory } from "vue-router";
import type { RouteRecordRaw } from "vue-router";
import ConnexionPage from "@/pages/ConnexionPage.vue";
import DashboardPage from "@/pages/DashboardPage.vue";
import HomePage from "@/pages/HomePage.vue";

const routes: Array<RouteRecordRaw> = [
  { path: "/", name: "Home", component: HomePage },
  { path: "/dashboard", name: "Dashboard", component: DashboardPage },
  { path: "/connexion", name: "Connecion", component: ConnexionPage },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
