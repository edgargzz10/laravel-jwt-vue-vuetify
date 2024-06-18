import { createRouter, createWebHistory } from "vue-router";
import HomePage from "@/pages/Home.vue";
import LoginPage from "@/pages/Login.vue";
import DashboardPage from "@/pages/Dashboard.vue";

const routes = [
  { path: "/", component: HomePage },
  { path: "/login", component: LoginPage, meta: { requiresGuest: true } }, // Agregar meta requiresGuest
  {
    path: "/dashboard",
    component: DashboardPage,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem("token");

  if (to.matched.some((record) => record.meta.requiresAuth) && !loggedIn) {
    next("/login"); // Redirigir a la página de login si intenta acceder a una ruta protegida sin autenticación
  } else if (
    to.matched.some((record) => record.meta.requiresGuest) &&
    loggedIn
  ) {
    next("/dashboard"); // Redirigir al dashboard si intenta acceder a las rutas de login o register estando autenticado
  } else {
    next(); // Permite la navegación hacia la ruta solicitada
  }
});

export default router;
