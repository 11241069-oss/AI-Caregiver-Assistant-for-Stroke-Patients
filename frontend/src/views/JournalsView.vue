<script setup>
import { ref, onMounted } from 'vue'

const journals = ref([])
const patients = ref([])
const form = ref({
    patient_id: '',
    tanggal: new Date().toISOString().split('T')[0],
    aktivitas: '',
    catatan: ''
})
const isLoading = ref(false)

const fetchPatients = async () => {
    const res = await fetch('http://localhost/IPPL_STROKE/api/pasien.php')
    const result = await res.json()
    if (result.success) patients.value = result.data
}

const fetchJournals = async () => {
    const res = await fetch('http://localhost/IPPL_STROKE/api/jurnal.php')
    const result = await res.json()
    if (result.success) journals.value = result.data
}

const simpanJurnal = async () => {
    if (!form.value.patient_id || !form.value.aktivitas) {
        return alert("Pasien dan Aktivitas wajib diisi!");
    }
    
    isLoading.value = true
    try {
        const res = await fetch('http://localhost/IPPL_STROKE/api/jurnal.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(form.value)
        })
        const result = await res.json()
        
        if (result.success) {
            alert("Jurnal berhasil disimpan!")
            form.value = { patient_id: '', tanggal: new Date().toISOString().split('T')[0], aktivitas: '', catatan: '' }
            fetchJournals()
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
    fetchJournals()
})
</script>

<template>
  <div class="page">
      <div class="page-heading">
          <div>
              <h1>Jurnal Harian</h1>
              <p>Dokumentasikan aktivitas dan catatan pendampingan.</p>
          </div>
      </div>

      <div class="panel">
          <div class="panel-heading"><h3>Tambah Jurnal</h3></div>
          <form @submit.prevent="simpanJurnal">
              <div class="form-grid">
                  <div>
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
                  <div class="full-width">
                      <label>Aktivitas</label>
                      <input type="text" v-model="form.aktivitas" placeholder="Contoh: latihan komunikasi" required>
                  </div>
                  <div class="full-width">
                      <label>Catatan</label>
                      <textarea v-model="form.catatan" placeholder="Ceritakan aktivitas dan respons pasien..."></textarea>
                  </div>
              </div>
              <button class="btn btn-primary" type="submit" :disabled="isLoading">
                  {{ isLoading ? 'Menyimpan...' : 'Simpan Jurnal' }}
              </button>
          </form>
      </div>

      <div class="panel">
          <div class="panel-heading"><h3>Riwayat Jurnal</h3></div>
          <div class="table-wrap">
              <table>
                  <thead>
                      <tr><th>Tanggal</th><th>Pasien</th><th>Aktivitas</th><th>Catatan</th></tr>
                  </thead>
                  <tbody>
                      <tr v-if="journals.length === 0">
                          <td colspan="4" class="empty">Belum ada jurnal.</td>
                      </tr>
                      <tr v-for="j in journals" :key="j.id">
                          <td>{{ j.tanggal }}</td>
                          <td><strong>{{ j.nama_pasien }}</strong></td>
                          <td>{{ j.aktivitas }}</td>
                          <td>{{ j.catatan }}</td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
</template>