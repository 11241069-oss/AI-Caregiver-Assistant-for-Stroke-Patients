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

      <!-- 4 KOTAK STATISTIK -->
      <div class="stats-grid">
          <div class="stat-card">
              <img src="/total_pasien.png" alt="Total Pasien" width="32" height="32" style="margin-bottom: 8px;">
              <div class="stat-label">Total Pasien</div>
              <div class="stat-number">{{ stats.patients }}</div>
          </div>

          <div class="stat-card">
              <img src="/observasi.png" alt="Total Observasi" width="32" height="32" style="margin-bottom: 8px;">
              <div class="stat-label">Total Observasi</div>
              <div class="stat-number">{{ stats.observations }}</div>
          </div>

          <div class="stat-card">
              <img src="/journal.png" alt="Jurnal Harian" width="32" height="32" style="margin-bottom: 8px;">
              <div class="stat-label">Jurnal Harian</div>
              <div class="stat-number">{{ stats.journals }}</div>
          </div>

          <div class="stat-card">
              <img src="/progress.png" alt="Catatan Progres" width="32" height="32" style="margin-bottom: 8px;">
              <div class="stat-label">Catatan Progres</div>
              <div class="stat-number">{{ stats.progress }}</div>
          </div>
      </div>

      <!-- 2 PANEL BAWAH -->
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