import { createRouter, createWebHistory } from "vue-router";
import type { RouteRecordRaw } from "vue-router";
import ConnexionPage from "@/pages/ConnexionPage.vue";
import DashboardPage from "@/pages/DashboardPage.vue";
import HomePage from "@/pages/HomePage.vue";
import HouseHoldPage from "@/pages/HouseHoldPage.vue";
import NourriturePage from "@/pages/NourriturePage.vue"
import EventPage from "@/pages/EventPage.vue";
import JoinHouseHoldPage from "@/pages/JoinHouseHoldPage.vue";
import {GetCookie} from "../services/index"


const routes: Array<RouteRecordRaw> = [
  { path: "/", name: "Home", component: HomePage },
  { path: "/dashboard", name: "Dashboard", component: DashboardPage },
  { path: "/connexion", name: "Connection", component: ConnexionPage, props: route => ({ mode : route.query.mode }) },
  { path: "/HouseHold", name: "HouseHold", component: HouseHoldPage },
  { path: "/nourriture", name: "Nourriture", component: NourriturePage },
  { path: "/events", name: "Events", component: EventPage }, 
  {path: "/JoinHouseHold", name: "JoinHouseHold", component: JoinHouseHoldPage}
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

// On check si l'utilisateur est connecté avant de pouvoir l'envoyer vers les pages utilisateurs 
// router.beforeEach((to,from,next) => {
//   if(to.name !== "Home" && to.name !== "Connection"){
//     const token = GetCookie("token");
//     console.log(token);
//     if(!token){
//       next({ name: "Connection", query: { mode: "connexion" } });
//     } else {
//       next();
//     }
//   }
//   else{
//     next();
//   }
// })

export default router;

