<template>
  <section class="login-screen">
    <div class="login-card">
      <div class="logo"></div>
      
      <h1>Buat Akun Baru</h1>
      <p class="subtitle">Daftar untuk mulai memantau pasien stroke Anda.</p>
      
      <div v-if="success" class="inline-success">✅ Pendaftaran berhasil! Mengalihkan ke Login...</div>
      
      <form @submit.prevent="handleRegister" v-else>
        <label>Nama Caregiver</label>
        <input type="text" v-model="name" placeholder="Masukkan nama" required>

        <label>Kata Sandi</label>
        <div class="input-wrapper">
          <input :type="showPass ? 'text' : 'password'" v-model="password" placeholder="Buat kata sandi (min. 8 karakter)" required>
<button type="button" class="toggle-pass" @click="showPass = !showPass">
  <!-- Ikon Mata Terbuka (Pas password disembunyiin) -->
  <svg v-if="!showPass" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
  </svg>
  
  <!-- Ikon Mata Dicoret (Pas password ditampilin) -->
  <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>
  </svg>
</button>
        </div>

        <label>Verifikasi Kata Sandi</label>
        <div class="input-wrapper">
          <input :type="showPassConfirm ? 'text' : 'password'" v-model="confirmPassword" placeholder="Ketik ulang kata sandi" required>
<button type="button" class="toggle-pass" @click="showPass = !showPass">
  <!-- Ikon Mata Terbuka (Pas password disembunyiin) -->
  <svg v-if="!showPass" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
  </svg>
  
  <!-- Ikon Mata Dicoret (Pas password ditampilin) -->
  <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>
  </svg>
</button>
        </div>
        <div class="error-message" :class="{show: error}">Kata sandi tidak cocok atau kurang dari 8 karakter.</div>

        <label>Pertanyaan Keamanan</label>
        <select v-model="question" required style="margin-bottom:10px;">
          <option value="">Pilih pertanyaan...</option>
          <option value="Nama hewan peliharaan pertama Anda?">Nama hewan peliharaan pertama Anda?</option>
          <option value="Nama sekolah dasar Anda?">Nama sekolah dasar Anda?</option>
          <option value="Kota kelahiran Ibu Anda?">Kota kelahiran Ibu Anda?</option>
        </select>
        <input type="text" v-model="answer" placeholder="Jawaban rahasia" required>

        <button class="btn btn-primary btn-full" type="submit" style="margin-top: 10px;">Daftar Sekarang</button>
      </form>
      
      <div class="auth-switch">Sudah punya akun? <router-link to="/">Masuk di sini</router-link></div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const name = ref('')
const password = ref('')
const confirmPassword = ref('')
const question = ref('')
const answer = ref('')
const showPass = ref(false)
const showPassConfirm = ref(false)
const error = ref(false)
const success = ref(false)

const handleRegister = async () => {
  error.value = false
  if (password.value.length < 8 || password.value !== confirmPassword.value) {
    error.value = true; return
  }

  try {
    const res = await fetch('http://localhost/IPPL_STROKE/api/auth.php', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'register', nama: name.value, password: password.value, pertanyaan: question.value, jawaban: answer.value })
    })
    const result = await res.json()
    
    if (result.success) {
      success.value = true
      setTimeout(() => { router.push('/') }, 2000)
    } else {
      alert(result.message)
    }
  } catch (err) { console.error(err) }
}
</script>