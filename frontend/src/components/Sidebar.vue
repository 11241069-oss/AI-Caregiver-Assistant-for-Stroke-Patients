<template>
  <aside id="sidebar" class="sidebar">
    <div class="brand">
      <div class="brand-icon" style="background: none; padding: 0; width: 40px; height: 40px;">
        <!-- LOGO APLIKASI -->
        <img src="/logo aplikasi.png" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
      </div>
      <div>AI Caregiver<br>Assistant</div>
    </div>

    <div class="nav-label">MENU UTAMA</div>
    
    <router-link to="/dashboard" class="nav-button" active-class="active">
      <img class="nav-icon" src="/dashboard.png" alt="Dashboard" width="20" height="20">
      Dashboard
    </router-link>
    
    <router-link to="/patients" class="nav-button" active-class="active">
      <img class="nav-icon" src="/total_pasien.png" alt="Data Pasien" width="20" height="20">
      Data Pasien
    </router-link>
    
    <router-link to="/observations" class="nav-button" active-class="active">
      <img class="nav-icon" src="/observasi.png" alt="Observasi" width="20" height="20">
      Observasi
    </router-link>
    
    <router-link to="/recommendations" class="nav-button" active-class="active">
      <img class="nav-icon" src="/rekomendasi.png" alt="Rekomendasi" width="20" height="20">
      Interpretasi & Rekomendasi
    </router-link>
    
    <router-link to="/journals" class="nav-button" active-class="active">
<img class="nav-icon" src="/journal.png" alt="Jurnal" width="20" height="20">      Jurnal Harian
    </router-link>
    
    <router-link to="/progress" class="nav-button" active-class="active">
      <img class="nav-icon" src="/progress.png" alt="Progres" width="20" height="20">
      Progres Pemulihan
    </router-link>

    <div class="nav-label">AKUN</div>
    <button @click="$emit('logout')" class="nav-button">
      <!-- Kalau nggak ada logo khusus buat logout, biarin pakai SVG teks atau hapus aja gambarnya -->
      <svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
      Keluar
    </button>
  </aside>
</template>