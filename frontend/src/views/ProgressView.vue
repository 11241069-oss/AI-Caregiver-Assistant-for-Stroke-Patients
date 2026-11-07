<script setup>
import { ref, onMounted, computed } from 'vue'

const progressList = ref([])
const patients = ref([])
const form = ref({
    patient_id: '',
    tanggal: new Date().toISOString().split('T')[0],
    skor: '',
    catatan: ''
})
const isLoading = ref(false)

const fetchPatients = async () => {
    const res = await fetch('http://localhost/IPPL_STROKE/api/pasien.php')
    const result = await res.json()
    if (result.success) patients.value = result.data
}

const fetchProgress = async () => {
    const res = await fetch('http://localhost/IPPL_STROKE/api/progres.php')
    const result = await res.json()
    if (result.success) progressList.value = result.data
}

const simpanProgress = async () => {
    if (!form.value.patient_id || form.value.skor === '') {
        return alert("Pasien dan Skor wajib diisi!");
    }
    
    isLoading.value = true
    try {
        const res = await fetch('http://localhost/IPPL_STROKE/api/progres.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(form.value)
        })
        const result = await res.json()
        
        if (result.success) {
            alert("Progres berhasil disimpan!")
            form.value = { patient_id: '', tanggal: new Date().toISOString().split('T')[0], skor: '', catatan: '' }
            fetchProgress()
        } else {
            alert("Gagal: " + result.message)
        }
    } catch (error) {
        alert("Error: " + error.message)
    } finally {
        isLoading.value = false
    }
}

// Menghitung Rata-rata Skor untuk Grafik Lingkaran
const averageScore = computed(() => {
    if (progressList.value.length === 0) return 0;
    const total = progressList.value.reduce((sum, item) => sum + Number(item.skor), 0);
    return Math.round(total / progressList.value.length);
})

// Warna garis lingkaran menyesuaikan skor
const circleColor = computed(() => {
    if (averageScore.value >= 75) return '#278765'; // Hijau (Stabil)
    if (averageScore.value >= 50) return '#F5B041'; // Kuning (Perlu perhatian)
    return '#C0392B'; // Merah (Evaluasi)
})

onMounted(() => {
    fetchPatients()
    fetchProgress()
})
</script>

<template>
  <div class="page">
      <div class="page-heading">
          <div>
              <h1>Progres Pemulihan</h1>
              <p>Catat perkembangan pasien dari waktu ke waktu.</p>
          </div>
      </div>
      
      <div class="notice">
          Skor progres adalah catatan subjektif untuk keperluan prototipe, bukan ukuran klinis.
      </div>

      <div class="dashboard-grid">
          <!-- FORM INPUT -->
          <div class="panel">
              <div class="panel-heading"><h3>Tambah Catatan Progres</h3></div>
              <form @submit.prevent="simpanProgress">
                  <div class="form-grid">
                      <div class="full-width">
                          <label>Pilih pasien</label>
                          <select v-model="form.patient_id" required>
                              <option value="">Pilih pasien</option>
                              <option v-for="p in patients" :key="p.id" :value="p.id">{{ p.nama }}</option>
                          </select>
                      </div>
                      <div>
                          <label>Tanggal</label>
                          <input type="date" v-model="form.tanggal" required>
                      </div>
                      <div>
                          <label>Skor progres (0–100)</label>
                          <input type="number" min="0" max="100" v-model="form.skor" placeholder="Contoh: 60" required>
                      </div>
                      <div class="full-width">
                          <label>Catatan perkembangan</label>
                          <textarea v-model="form.catatan" placeholder="Tuliskan perubahan yang diamati..."></textarea>
                      </div>
                  </div>
                  <button class="btn btn-primary" type="submit" :disabled="isLoading">
                      {{ isLoading ? 'Menyimpan...' : 'Simpan Progres' }}
                  </button>
              </form>
          </div>

          <!-- GRAFIK LINGKARAN RATA-RATA -->
          <div class="panel" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
              <div class="panel-heading"><h3>Rata-rata Progres</h3></div>
              
              <div class="circular-chart" :style="{ background: `conic-gradient(${circleColor} ${averageScore}%, #e8e0f1 ${averageScore}% 100%)` }">
                  <div class="circle-inner">
                      <span class="score-number">{{ averageScore }}%</span>
                      <span class="score-label">Rata-rata</span>
                  </div>
              </div>
              <p style="margin-top: 15px; text-align: center;">Berdasarkan {{ progressList.length }} catatan terakhir.</p>
          </div>
      </div>

      <!-- TABEL RIWAYAT -->
      <div class="panel">
          <div class="panel-heading"><h3>Riwayat Progres</h3></div>
          <div class="table-wrap">
              <table>
                  <thead>
                      <tr><th>Tanggal</th><th>Pasien</th><th>Skor</th><th>Catatan</th></tr>
                  </thead>
                  <tbody>
                      <tr v-if="progressList.length === 0">
                          <td colspan="4" class="empty">Belum ada progres.</td>
                      </tr>
                      <tr v-for="p in progressList" :key="p.id">
                          <td>{{ p.tanggal }}</td>
                          <td><strong>{{ p.nama_pasien }}</strong></td>
                          <td><strong>{{ p.skor }}/100</strong></td>
                          <td>{{ p.catatan }}</td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
</template>

<style scoped>
/* CSS UNTUK GRAFIK LINGKARAN (PIE CHART) */
.circular-chart {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    /* Animasi saat grafik muncul */
    transition: background 0.5s ease-in-out;
}

.circle-inner {
    width: 140px;
    height: 140px;
    background-color: white;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
}

.score-number {
    font-size: 32px;
    font-weight: 800;
    color: #302744;
}

.score-label {
    font-size: 12px;
    color: #81768f;
    font-weight: 700;
}
</style>