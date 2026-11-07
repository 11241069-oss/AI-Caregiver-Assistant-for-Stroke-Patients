<script setup>
import { ref, onMounted } from 'vue'

const stats = ref({ patients: 0, observations: 0, journals: 0, progress: 0 })
const recentPatients = ref([])

const fetchStats = async () => {
    try {
        const resPasien = await fetch('http://localhost/IPPL_STROKE/api/pasien.php')
        const dataPasien = await resPasien.json()
        if (dataPasien.success) {
            stats.value.patients = dataPasien.data.length
            recentPatients.value = dataPasien.data.slice(0, 5)
        }
    } catch (error) {
        console.error("Gagal mengambil data:", error)
    }
}
onMounted(() => { fetchStats() })
</script>

<template>
  <div>
      <div class="page-heading">
          <div>
<h1>Halo, <span>Caregiver</span>!</h1>
              <p>Mari pantau perkembangan pasien hari ini.</p>
          </div>
          <router-link to="/patients" class="btn btn-primary">+ Tambah Pasien</router-link>
      </div>

      <div class="notice success">Terhubung ke Database MySQL.</div>

      <div class="stats-grid">
  <!-- KOTAK 1: TOTAL PASIEN -->
  <div class="stat-card">
      <div class="stat-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--purple);">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
          <circle cx="12" cy="3" r="2.5"></circle>
          <line x1="12" y1="1.5" x2="12" y2="4.5"></line>
          <line x1="10.5" y1="3" x2="13.5" y2="3"></line>
        </svg>
      </div>
      <div class="stat-label">Total Pasien</div>
      <div class="stat-number">{{ stats.patients }}</div>
  </div>

  <!-- KOTAK 2: TOTAL OBSERVASI -->
  <div class="stat-card">
      <div class="stat-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--purple);">
          <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14h6"></path><path d="M9 18h6"></path><path d="M9 10h6"></path>
        </svg>
      </div>
      <div class="stat-label">Total Observasi</div>
      <div class="stat-number">{{ stats.observations }}</div>
  </div>

  <!-- KOTAK 3: JURNAL HARIAN -->
  <div class="stat-card">
      <div class="stat-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--purple);">
          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
        </svg>
      </div>
      <div class="stat-label">Jurnal Harian</div>
      <div class="stat-number">{{ stats.journals }}</div>
  </div>

  <!-- KOTAK 4: CATATAN PROGRES -->
  <div class="stat-card">
      <div class="stat-icon">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--purple);">
          <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
        </svg>
      </div>
      <div class="stat-label">Catatan Progres</div>
      <div class="stat-number">{{ stats.progress }}</div>
  </div>
</div>

      <div class="dashboard-grid">
          <div class="panel">
              <div class="panel-heading" style="display:flex; justify-content:space-between;">
                  <h3>Pasien Terdaftar</h3>
                  <router-link to="/patients" class="btn btn-small">Lihat semua</router-link>
              </div>
              <div v-if="recentPatients.length === 0" class="empty" style="text-align:center; color:var(--muted);">Belum ada pasien.</div>
              <div v-else>
                  <div v-for="p in recentPatients" :key="p.id" class="record-item" style="padding:13px; border:1px solid var(--border); border-radius:11px; margin-bottom:10px;">
                      <strong>{{ p.nama }}</strong><br>
                      <small style="color:var(--muted);">{{ p.usia }} tahun · {{ p.kondisi }}</small>
                  </div>
              </div>
          </div>
          <div class="panel">
              <div class="panel-heading"><h3>Aktivitas Terbaru</h3></div>
              <div class="empty" style="text-align:center; color:var(--muted);">Belum ada aktivitas.</div>
          </div>
      </div>
  </div>
</template>