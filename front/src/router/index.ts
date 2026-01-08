import { createRouter, createWebHistory } from "vue-router";
import type { RouteRecordRaw } from "vue-router";
import ConnexionPage from "@/pages/ConnexionPage.vue";
import DashboardPage from "@/pages/DashboardPage.vue";
import HomePage from "@/pages/HomePage.vue";
import HouseHoldHandler from "@/pages/JoinHouseHoldPage.vue";
import RecipePage from "@/pages/RecipePage.vue"
import HouseHoldPage from "@/pages/HouseHoldPage.vue";
import EventPage from "@/pages/EventPage.vue";
import JoinHouseHoldPage from "@/pages/JoinHouseHoldPage.vue";
import AddPersonHouseHoldPage from "@/pages/AddPersonHouseHoldPage.vue";
import FamillyPage from "@/pages/FamillyPage.vue";
import ChoresPage from "@/pages/ChoresPage.vue"
import ShoppingListPage from "@/pages/ShoppingListPage.vue"
import {GetCookie} from "../services/index"


const routes: Array<RouteRecordRaw> = [
  { path: "/", name: "Home", component: HomePage },
  { path: "/dashboard", name: "Dashboard", component: DashboardPage },
  { path: "/connexion", name: "Connection", component: ConnexionPage, props: route => ({ mode : route.query.mode }) },
  { path: "/HouseHold", name: "HouseHold", component: HouseHoldHandler },
  { path: "/recettes", name: "Recettes", component: RecipePage },
  { path: "/events", name: "Events", component: EventPage }, 
  {path: "/JoinHouseHold", name: "JoinHouseHold", component: JoinHouseHoldPage},
  {path: "/AddPersonHouseHold", name: "AddPersonHouseHold", component: AddPersonHouseHoldPage},
  {path: "/Familly", name: "Familly", component: FamillyPage},
  {path: "/chores", name: "Chores", component : ChoresPage},
  {path: "/shopping-lists/:id", name: "shopping-list", component: ShoppingListPage}

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

