<template>
  <header class="topbar">
    <div style="display:flex;align-items:center;gap:12px">
      <button class="btn mobile-menu" @click="$emit('toggleSidebar')" style="display:none;">☰</button>
      <div>
        <strong>AI Caregiver Assistant</strong>
        <p>AI Caregiver Assistant for Stroke Patients</p>
      </div>
    </div>
<div class="user-pill" style="display: flex; align-items: center; gap: 8px;">
  <!-- AVATAR SVG -->
  <svg width="28" height="28" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Background Biru -->
    <circle cx="50" cy="50" r="50" fill="#3b5b9b"/>
    <!-- Badan / Kemeja -->
    <path d="M20 90 C20 70, 35 65, 50 65 C65 65, 80 70, 80 90" fill="#9eb5df"/>
    <path d="M35 65 L50 80 L65 65" fill="#748cc7"/> <!-- Lipatan kemeja -->
    <!-- Leher -->
    <rect x="40" y="50" width="20" height="20" fill="#fcd5b4"/>
    <!-- Kepala & Telinga -->
    <ellipse cx="50" cy="40" rx="22" ry="25" fill="#fcd5b4"/>
    <circle cx="28" cy="40" r="4" fill="#fcd5b4"/>
    <circle cx="72" cy="40" r="4" fill="#fcd5b4"/>
    <!-- Rambut -->
    <path d="M28 30 C28 15, 72 15, 72 30 C75 35, 70 25, 50 25 C30 25, 25 35, 28 30 Z" fill="#5c3a21"/>
  </svg>
  <span>{{ caregiverName }}</span>
</div>
 </header>
</template>

<script setup>
defineProps({
  caregiverName: { type: String, default: 'Caregiver' }
})
defineEmits(['toggleSidebar'])
</script>

<style scoped>
/* Khusus tombol mobile di layar kecil */
@media (max-width: 720px) {
  .mobile-menu { display: inline-block !important; }
}
</style>