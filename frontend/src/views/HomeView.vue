<script setup>
import { ref, onMounted } from 'vue'

const stats = ref({
    patients: 0,
    observations: 0,
    journals: 0,
    progress: 0
})
const recentPatients = ref([])

const fetchStats = async () => {
    try {
        const resPasien = await fetch('http://localhost/IPPL_STROKE/api/pasien.php')
        const dataPasien = await resPasien.json()
        if (dataPasien.success) {
            stats.value.patients = dataPasien.data.length
            recentPatients.value = dataPasien.data.slice(0, 5)
        }

        const resObs = await fetch('http://localhost/IPPL_STROKE/api/observasi.php')
        const dataObs = await resObs.json()
        if (dataObs.success) stats.value.observations = dataObs.data.length

        const resJurnal = await fetch('http://localhost/IPPL_STROKE/api/jurnal.php')
        const dataJurnal = await resJurnal.json()
        if (dataJurnal.success) stats.value.journals = dataJurnal.data.length

        const resProgress = await fetch('http://localhost/IPPL_STROKE/api/progres.php')
        const dataProgress = await resProgress.json()
        if (dataProgress.success) stats.value.progress = dataProgress.data.length

    } catch (error) {
        console.error("Gagal mengambil data statistik:", error)
    }
}

onMounted(() => {
    fetchStats()
})
</script>

<template>
  <div class="page">
      <div class="page-heading">
          <div>
              <h1>Halo, Caregiver! 💜</h1>
              <p>Mari pantau perkembangan pasien hari ini.</p>
          </div>
          <router-link to="/patients" class="btn btn-primary">+ Tambah Pasien</router-link>
      </div>

      <div class="notice success">Terhubung ke Database MySQL.</div>

      <div class="stats-grid">
          <div class="stat-card">
              <div class="stat-icon">👥</div>
              <div class="stat-label">Total Pasien</div>
              <div class="stat-number">{{ stats.patients }}</div>
          </div>
          <div class="stat-card">
              <div class="stat-icon">📝</div>
              <div class="stat-label">Total Observasi</div>
              <div class="stat-number">{{ stats.observations }}</div>
          </div>
          <div class="stat-card">
              <div class="stat-icon">📔</div>
              <div class="stat-label">Jurnal Harian</div>
              <div class="stat-number">{{ stats.journals }}</div>
          </div>
          <div class="stat-card">
              <div class="stat-icon">📈</div>
              <div class="stat-label">Catatan Progres</div>
              <div class="stat-number">{{ stats.progress }}</div>
          </div>
      </div>

      <div class="panel" style="height: fit-content;">
          <div class="panel-heading">
              <h3>Pasien Terdaftar</h3>
              <router-link to="/patients" class="btn btn-small">Lihat semua</router-link>
          </div>
          <div v-if="recentPatients.length === 0" class="empty">Belum ada pasien.</div>
          <div v-else class="record-list">
              <div v-for="p in recentPatients" :key="p.id" class="record-item">
                  <strong>{{ p.nama }}</strong>
                  <small>{{ p.usia }} tahun · {{ p.kondisi }}</small>
              </div>
          </div>
      </div>
  </div>
</template>