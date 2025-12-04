import { createRouter, createWebHistory } from "vue-router";
import type { RouteRecordRaw } from "vue-router";
import ConnexionPage from "@/pages/ConnexionPage.vue";
import DashboardPage from "@/pages/DashboardPage.vue";
import HomePage from "@/pages/HomePage.vue";
import HouseHoldHandler from "@/pages/JoinHouseHoldPage.vue";

const routes: Array<RouteRecordRaw> = [
  { path: "/", name: "Home", component: HomePage },
  { path: "/dashboard", name: "Dashboard", component: DashboardPage },
  { path: "/connexion", name: "Connection", component: ConnexionPage, props: route => ({ mode : route.query.mode }) },
  { path: "/HouseHold", name: "HouseHold", component: HouseHoldHandler },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
