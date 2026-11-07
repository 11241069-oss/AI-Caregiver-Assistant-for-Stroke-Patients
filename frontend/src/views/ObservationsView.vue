<script setup>
import { ref, onMounted } from 'vue'

const observations = ref([])
const patients = ref([])
const form = ref({
    patient_id: '',
    tanggal: new Date().toISOString().split('T')[0], // Default hari ini
    tekanan_darah: '',
    gula_darah: '',
    gejala_klinis: '',
    tingkat_perhatian: 'Stabil',
    catatan: ''
})
const isLoading = ref(false)

// Ambil data pasien (untuk dropdown)
const fetchPatients = async () => {
    const res = await fetch('http://localhost/IPPL_STROKE/api/pasien.php')
    const result = await res.json()
    if (result.success) patients.value = result.data
}

// Ambil data observasi
const fetchObservations = async () => {
    const res = await fetch('http://localhost/IPPL_STROKE/api/observasi.php')
    const result = await res.json()
    if (result.success) observations.value = result.data
}

// Simpan observasi
const simpanObservasi = async () => {
    if (!form.value.patient_id || !form.value.tekanan_darah || !form.value.gula_darah) {
        return alert("Pasien, Tekanan Darah, dan Gula Darah wajib diisi!");
    }
    
    isLoading.value = true
    try {
        const res = await fetch('http://localhost/IPPL_STROKE/api/observasi.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(form.value)
        })
        const result = await res.json()
        
        if (result.success) {
            alert("Observasi berhasil disimpan!")
            form.value = { patient_id: '', tanggal: new Date().toISOString().split('T')[0], tekanan_darah: '', gula_darah: '', gejala_klinis: '', tingkat_perhatian: 'Stabil', catatan: '' }
            fetchObservations()
        } else {
            alert("Gagal: " + result.message)
        }
    } catch (error) {
        alert("Error: " + error.message)
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    fetchPatients()
    fetchObservations()
})
</script>

<template>
  <div class="page">
      <div class="page-heading">
          <div>
              <h1>Observasi Pasien</h1>
              <p>Catat tanda vital dan gejala klinis pasien.</p>
          </div>
      </div>
      
      <div class="notice warning">
          Interpretasi aplikasi hanya berupa bantuan pencatatan. Hasilnya bukan diagnosis medis.
      </div>

      <div class="panel">
          <div class="panel-heading"><h3>Tambah Observasi</h3></div>
          <form @submit.prevent="simpanObservasi">
              <div class="form-grid">
                  <div>
                      <label>Pilih pasien</label>
                      <select v-model="form.patient_id" required>
                          <option value="">Pilih pasien</option>
                          <option v-for="p in patients" :key="p.id" :value="p.id">{{ p.nama }}</option>
                      </select>
                  </div>
                  <div>
                      <label>Tanggal observasi</label>
                      <input type="date" v-model="form.tanggal" required>
                  </div>
                  <div>
                      <label>Tekanan Darah (mmHg)</label>
                      <input type="text" v-model="form.tekanan_darah" placeholder="Contoh: 120/80" required>
                  </div>
                  <div>
                      <label>Gula Darah (mg/dL)</label>
                      <input type="number" v-model="form.gula_darah" placeholder="Contoh: 140" required>
                  </div>
                  <div>
                      <label>Gejala Klinis</label>
                      <select v-model="form.gejala_klinis">
                          <option value="">Pilih gejala...</option>
                          <option value="Senyum Tidak Simetris (Mencong)">Senyum Tidak Simetris</option>
                          <option value="Gerak Separuh Anggota Tubuh Melemah">Gerak Melemah</option>
                          <option value="Bicara Pelo / Tidak Jelas">Bicara Pelo</option>
                          <option value="Kebas / Baal / Kesemutan Separuh Tubuh">Kebas/Baal</option>
                          <option value="Rabun / Pandangan Kabur Mendadak">Pandangan Kabur</option>
                          <option value="Sakit Kepala Hebat / Pusing Berputar">Sakit Kepala Hebat</option>
                          <option value="Gangguan Menelan (Disfagia)">Gangguan Menelan</option>
                      </select>
                  </div>
                  <div>
                      <label>Tingkat perhatian</label>
                      <select v-model="form.tingkat_perhatian" required>
                          <option>Stabil</option>
                          <option>Perlu perhatian</option>
                          <option>Perlu evaluasi medis</option>
                      </select>
                  </div>
                  <div class="full-width">
                      <label>Catatan observasi</label>
                      <textarea v-model="form.catatan" placeholder="Tuliskan hal yang diamati..."></textarea>
                  </div>
              </div>
              <button class="btn btn-primary" type="submit" :disabled="isLoading">
                  {{ isLoading ? 'Menyimpan...' : 'Simpan Observasi' }}
              </button>
          </form>
      </div>

      <div class="panel">
          <div class="panel-heading"><h3>Riwayat Observasi</h3></div>
          <div class="table-wrap">
              <table>
                  <thead>
                      <tr><th>Tanggal</th><th>Pasien</th><th>Tanda Vital (TD / GDS)</th><th>Gejala</th><th>Tingkat</th></tr>
                  </thead>
                  <tbody>
                      <tr v-if="observations.length === 0">
                          <td colspan="5" class="empty">Belum ada observasi.</td>
                      </tr>
                      <tr v-for="o in observations" :key="o.id">
                          <td>{{ o.tanggal }}</td>
                          <td><strong>{{ o.nama_pasien }}</strong></td>
                          <td>{{ o.tekanan_darah }} mmHg / {{ o.gula_darah }} mg/dL</td>
                          <td>{{ o.gejala_klinis }}</td>
                          <td><span class="badge">{{ o.tingkat_perhatian }}</span></td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
</template>