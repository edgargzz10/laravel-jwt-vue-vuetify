import { createRouter, createWebHistory } from "vue-router";
import HomePage from "@/pages/Home.vue";
import LoginPage from "@/pages/Login.vue";
import DashboardPage from "@/pages/Dashboard.vue";

const routes = [
  { path: "/", component: HomePage },
  { path: "/login", component: LoginPage, meta: { requiresGuest: true } },
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
    next("/login");
  } else if (
    to.matched.some((record) => record.meta.requiresGuest) &&
    loggedIn
  ) {
    next("/dashboard");
  } else {
    next();
  }
});

export default router;
