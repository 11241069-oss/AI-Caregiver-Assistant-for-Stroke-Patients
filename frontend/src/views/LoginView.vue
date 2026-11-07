<template>
  <section class="login-screen">
    <div class="login-card">
      
      <!-- LOGO DIPERBESAR & KE TENGAH -->
      <div style="display: flex; justify-content: center; margin-bottom: 20px;">
        <div class="logo" style="width: 100px; height: 100px; margin: 0; padding: 10px; display: flex; align-items: center; justify-content: center;">
          <img src="/logo.png" alt="Logo Caregiver" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
      </div>
      
      <h1 style="text-align: center;">AI Caregiver Assistant</h1>
      <p class="subtitle" style="text-align: center;">Asisten pendamping untuk membantu pemantauan aktivitas dan pemulihan pasien stroke.</p>
      
      <form @submit.prevent="handleLogin">
        <label for="loginName">Nama Caregiver</label>
        <input type="text" id="loginName" v-model="name" placeholder="Masukkan nama Anda" :class="{'input-error': error.name}">
        <div class="error-message" :class="{show: error.name}">Nama minimal 3 karakter.</div>

        <label for="loginPassword">Kata Sandi</label>
        <div class="input-wrapper">
          <input :type="showPass ? 'text' : 'password'" id="loginPassword" v-model="password" placeholder="Masukkan kata sandi" :class="{'input-error': error.password}">
          <button type="button" class="toggle-pass" @click="showPass = !showPass">
            <svg v-if="!showPass" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
            </svg>
            <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>
            </svg>
          </button>
        </div>
        <div class="error-message" :class="{show: error.password}">{{ error.passwordMsg }}</div>

        <div style="text-align:right; margin-bottom: 15px; margin-top: -5px;">
          <a class="link-btn" @click="$emit('show-forgot')" style="font-size: 13px; cursor:pointer;">Lupa Password?</a>
        </div>

        <button class="btn btn-primary btn-full" type="submit">Masuk ke Dashboard</button>
      </form>
      
      <div class="auth-switch">Belum punya akun? <router-link to="/register">Daftar di sini</router-link></div>
    </div>
  </section>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const name = ref('')
const password = ref('')
const showPass = ref(false)
const error = reactive({ name: false, password: false, passwordMsg: '' })

const handleLogin = async () => {
  error.name = false; error.password = false; error.passwordMsg = ''
  if (name.value.length < 3) { error.name = true; return }

  try {
    const res = await fetch('http://localhost/IPPL_STROKE/api/auth.php', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'login', nama: name.value, password: password.value })
    })
    const result = await res.json()
    
    if (result.success) {
      localStorage.setItem('isLoggedIn', 'true')
      localStorage.setItem('caregiverName', result.nama)
      router.push('/dashboard')
    } else {
      error.password = true
      error.passwordMsg = result.message
    }
  } catch (err) { alert("Gagal terhubung ke server.") }
}
</script>