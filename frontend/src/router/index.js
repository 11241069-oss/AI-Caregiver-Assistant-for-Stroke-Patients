import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'login', component: LoginView },
    { path: '/register', name: 'register', component: () => import('../views/RegisterView.vue') },
    { path: '/dashboard', name: 'dashboard', component: () => import('../views/DashboardView.vue') },
    { path: '/patients', name: 'patients', component: () => import('../views/PatientsView.vue') },
    { path: '/observations', name: 'observations', component: () => import('../views/ObservationsView.vue') },
    { path: '/recommendations', name: 'recommendations', component: () => import('../views/RecommendationsView.vue') },
    { path: '/journals', name: 'journals', component: () => import('../views/JournalsView.vue') },
    { path: '/progress', name: 'progress', component: () => import('../views/ProgressView.vue') },
    { path: '/:pathMatch(.*)*', redirect: '/dashboard' }
  ]
})

router.beforeEach((to, from, next) => {
  const isLoggedIn = localStorage.getItem('isLoggedIn')
  if (isLoggedIn && (to.path === '/' || to.path === '/register')) {
    next('/dashboard')
  } else if (!isLoggedIn && to.path !== '/' && to.path !== '/register') {
    next('/')
  } else {
    next()
  }
})

export default router