<script setup>
import { ref, onMounted } from 'vue'

// State untuk menampung data
const patients = ref([])
const form = ref({
    nama: '',
    usia: '',
    kondisi: '',
    status: 'Sedang didampingi'
})
const isLoading = ref(false)

// Fungsi mengambil data dari PHP
const fetchPatients = async () => {
    try {
        const response = await fetch('http://localhost/IPPL_STROKE/api/pasien.php')
        const result = await response.json()
        if (result.success) {
            patients.value = result.data
        }
    } catch (error) {
        console.error("Gagal mengambil data:", error)
    }
}

// Fungsi mengirim data ke PHP
const simpanPasien = async () => {
    if (!form.value.nama || !form.value.usia || !form.value.kondisi) {
        return alert("Nama, Usia, dan Gejala Klinis wajib diisi!");
    }
    
    isLoading.value = true
    try {
        const response = await fetch('http://localhost/IPPL_STROKE/api/pasien.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(form.value)
        })
        const result = await response.json()
        
        if (result.success) {
            alert("Pasien berhasil disimpan!")
            form.value = { nama: '', usia: '', kondisi: '', status: 'Sedang didampingi' }
            fetchPatients()
        } else {
            alert("Gagal menyimpan: " + result.message)
        }
    } catch (error) {
        alert("Error: " + error.message)
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    fetchPatients()
})
</script>

<template>
  <div class="page">
      <div class="page-heading">
          <div>
              <h1>Data Pasien</h1>
              <p>Kelola informasi pasien yang didampingi.</p>
          </div>
      </div>

      <div class="panel">
          <div class="panel-heading"><h3>Tambah Pasien</h3></div>
          <form @submit.prevent="simpanPasien">
              <div class="form-grid">
                  <div>
                      <label>Nama pasien</label>
                      <input type="text" v-model="form.nama" placeholder="Nama lengkap">
                  </div>
                  <div>
                      <label>Usia</label>
                      <input type="number" v-model="form.usia" placeholder="Usia">
                  </div>
                  
                  <div>
                      <label>Gejala Klinis (SeGeRa Ke RS)</label>
                      <select v-model="form.kondisi">
                          <option value="">Pilih gejala klinis...</option>
                          <optgroup label="Deteksi Dini Stroke (Kemenkes RI)">
                              <option value="Senyum Tidak Simetris (Mencong)">Senyum Tidak Simetris (Mencong)</option>
                              <option value="Gerak Separuh Anggota Tubuh Melemah">Gerak Separuh Anggota Tubuh Melemah</option>
                              <option value="Bicara Pelo / Tidak Jelas">Bicara Pelo / Tidak Jelas</option>
                              <option value="Kebas / Baal / Kesemutan Separuh Tubuh">Kebas / Baal / Kesemutan Separuh Tubuh</option>
                              <option value="Rabun / Pandangan Kabur Mendadak">Rabun / Pandangan Kabur Mendadak</option>
                              <option value="Sakit Kepala Hebat / Pusing Berputar">Sakit Kepala Hebat / Pusing Berputar</option>
                          </optgroup>
                          <optgroup label="Kondisi Fungsional (Dampak Lanjutan)">
                              <option value="Gangguan Menelan (Disfagia)">Gangguan Menelan (Disfagia)</option>
                              <option value="Penurunan Kesadaran">Penurunan Kesadaran</option>
                              <option value="Ketergantungan Total (ADL)">Ketergantungan Total (ADL)</option>
                          </optgroup>
                      </select>
                  </div>

                  <div>
                      <label>Status pendampingan</label>
                      <select v-model="form.status">
                          <option value="Sedang didampingi">Sedang didampingi</option>
                          <option value="Selesai didampingi">Selesai didampingi</option>
                      </select>
                  </div>
              </div>
              <div class="action-row">
                  <button class="btn btn-primary" type="submit" :disabled="isLoading">
                      {{ isLoading ? 'Menyimpan...' : 'Simpan Pasien' }}
                  </button>
              </div>
          </form>
      </div>

      <div class="panel">
          <div class="panel-heading"><h3>Daftar Pasien</h3></div>
          <div class="table-wrap">
              <table>
                  <thead>
                      <tr><th>Nama</th><th>Usia</th><th>Gejala Klinis</th><th>Status</th><th>Aksi</th></tr>
                  </thead>
                  <tbody>
                      <tr v-if="patients.length === 0">
                          <td colspan="5" class="empty">Belum ada pasien.</td>
                      </tr>
                      <tr v-for="p in patients" :key="p.id">
                          <td><strong>{{ p.nama }}</strong></td>
                          <td>{{ p.usia }} tahun</td>
                          <td><span class="badge">{{ p.kondisi }}</span></td>
                          <td><span class="badge">{{ p.status }}</span></td>
                          <td>
                             <button class="btn btn-small btn-danger">Hapus</button>
                          </td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>
  </div>
</template>