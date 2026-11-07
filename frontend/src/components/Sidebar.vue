<template>
  <aside id="sidebar" class="sidebar">
    <div class="brand">
      <div class="brand-icon">
        <!-- Ikon Caregiver & Pasien Stroke -->
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--purple);">
          <circle cx="6" cy="5" r="2"></circle>
          <path d="M6 7v7"></path>
          <path d="M4 10h4"></path>
          <path d="M4 14h4"></path>
          <circle cx="14" cy="18" r="3"></circle>
          <path d="M14 15v3h3"></path>
          <path d="M11 13h3"></path>
          <path d="M8 10h3"></path>
          <path d="M19 6a3 3 0 0 0-4 0l-1 1-1-1a3 3 0 0 0-4 4l1 1 4 4 4-4 1-1a3 3 0 0 0 0-4z" transform="scale(0.7) translate(8, 3)"></path>
          <path d="M17.5 8.5v3M16 10h3" transform="scale(0.7) translate(8, 3)"></path>
        </svg>
      </div>
      <div>AI Caregiver<br>Assistant</div>
    </div>

    <div class="nav-label">MENU UTAMA</div>
    
    <router-link to="/dashboard" class="nav-button" active-class="active">
      <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
      Dashboard
    </router-link>
    
    <router-link to="/patients" class="nav-button" active-class="active">
      <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
      Data Pasien
    </router-link>
    
    <router-link to="/observations" class="nav-button" active-class="active">
      <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14h6"></path><path d="M9 18h6"></path><path d="M9 10h6"></path></svg>
      Observasi
    </router-link>
    
    <router-link to="/recommendations" class="nav-button" active-class="active">
      <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"></path></svg>
      Interpretasi & Rekomendasi
    </router-link>
    
    <router-link to="/journals" class="nav-button" active-class="active">
      <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
      Jurnal Harian
    </router-link>
    
    <router-link to="/progress" class="nav-button" active-class="active">
      <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
      Progres Pemulihan
    </router-link>

    <div class="nav-label">AKUN</div>
    <button @click="$emit('logout')" class="nav-button">
      <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
      Keluar
    </button>
  </aside>
</template>

<script setup>
defineEmits(['logout'])
</script>

<style scoped>
/* CSS Sidebar (Sama persis kayak punyamu) */
.sidebar {
  width: 250px;
  background-color: #ffffff;
  border-right: 1px solid #e5e7eb;
  padding: 20px;
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: sticky;
  top: 0;
}
.brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 20px;
  color: #1f2937;
}
.brand-icon {
  background-color: #f3e8ff;
  padding: 8px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.nav-label {
  font-size: 11px;
  color: #9ca3af;
  font-weight: bold;
  margin-top: 20px;
  margin-bottom: 8px;
  padding-left: 10px;
}
.nav-button {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 12px;
  text-decoration: none;
  color: #4b5563;
  border-radius: 8px;
  background: none;
  border: none;
  cursor: pointer;
  text-align: left;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.2s;
  margin-bottom: 4px;
  width: 100%;
}
.nav-button:hover {
  background-color: #f3f4f6;
}
.nav-button.active {
  background-color: #f3e8ff;
  color: #7e22ce;
  font-weight: 600;
}
.nav-icon {
  flex-shrink: 0;
}
</style>