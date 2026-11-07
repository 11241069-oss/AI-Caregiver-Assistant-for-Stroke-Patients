<template>
  <div>
    <!-- JIKA BELUM LOGIN -->
    <div v-if="!isLoggedIn">
      <ForgotPasswordView v-if="showForgotModal" @close="showForgotModal = false" />
      <router-view @show-forgot="showForgotModal = true"></router-view>
    </div>

    <!-- JIKA SUDAH LOGIN -->
    <div v-else class="app-layout">
      <Sidebar @logout="handleLogout" />
      <main class="main-area">
        <Navbar :caregiverName="caregiverName" @toggleSidebar="toggleSidebar" />
        <div class="content">
          <router-view></router-view>
        </div>
        <footer>AI Caregiver Assistant for Stroke Patients · Prototipe akademik</footer>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Sidebar from '@/components/Sidebar.vue'
import Navbar from '@/components/Navbar.vue'
import ForgotPasswordView from '@/views/ForgotPasswordView.vue'

const router = useRouter()
const isLoggedIn = ref(false)
const caregiverName = ref('Caregiver')
const showForgotModal = ref(false)

onMounted(() => {
  const loginStatus = localStorage.getItem('isLoggedIn')
  const name = localStorage.getItem('caregiverName')
  if (loginStatus === 'true' && name) {
    isLoggedIn.value = true
    caregiverName.value = name
  }
})

const handleLogout = () => {
  localStorage.removeItem('isLoggedIn')
  localStorage.removeItem('caregiverName')
  isLoggedIn.value = false
  caregiverName.value = 'Caregiver'
  router.push('/')
}

const toggleSidebar = () => {
  const sidebar = document.querySelector('.sidebar')
  if (sidebar) sidebar.classList.toggle('open')
}
</script>

<style>
/* CSS Dasar Layout (Diambil dari file HTML aslimu) */
body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #faf8fd; color: var(--text); }
.app-layout { min-height: 100vh; display: flex; }
.main-area { flex: 1; min-width: 0; display: flex; flex-direction: column; }
.content { padding: 28px; max-width: 1500px; margin: auto; width: 100%; flex: 1; }
footer { padding: 20px 28px; font-size: 11px; color: var(--muted); text-align: center; margin-top: auto; }
@media (max-width: 720px) { 
  .sidebar { position: fixed; z-index: 10; top: 0; bottom: 0; left: 0; transform: translateX(-105%); transition: transform .2s ease; box-shadow: var(--shadow); } 
  .sidebar.open { transform: translateX(0); } 
}
</style>