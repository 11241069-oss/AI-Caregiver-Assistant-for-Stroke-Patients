<template>
  <div class="modal-overlay">
    <div class="modal-card">
      <button class="modal-close" @click="$emit('close')">✖</button>
      <h2 style="margin-bottom:20px;">Reset Password</h2>
      
      <!-- Step 1: Cari Akun -->
      <div v-if="step === 1">
        <p style="font-size:13px; margin-bottom:15px;">Masukkan nama akunmu untuk mereset password.</p>
        <label>Nama Caregiver</label>
        <input type="text" v-model="name" placeholder="Nama akunmu">
        <div class="error-message" :class="{show: error1}">{{ errorMsg }}</div>
        <button class="btn btn-primary btn-full" @click="checkAccount">Lanjut</button>
      </div>

      <!-- Step 2: Pertanyaan & Password Baru -->
      <div v-if="step === 2">
        <p style="font-weight:bold; color:var(--text); margin-bottom:15px; font-size:14px;">Pertanyaan: {{ question }}</p>
        
        <label>Jawaban Keamanan</label>
        <input type="text" v-model="answer" placeholder="Jawaban rahasia">
        
        <label>Password Baru</label>
        <div class="input-wrapper">
          <input :type="showPass ? 'text' : 'password'" v-model="newPassword" placeholder="Password baru (min. 8 karakter)">
          <button type="button" class="toggle-pass" @click="showPass = !showPass"><span v-if="showPass">👁️</span><svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></button>
        </div>
        
        <div class="error-message" :class="{show: error2}">{{ errorMsg2 }}</div>
        <button class="btn btn-primary btn-full" @click="resetPassword">Simpan Password Baru</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const emit = defineEmits(['close'])
const step = ref(1)
const name = ref('')
const question = ref('')
const answer = ref('')
const newPassword = ref('')
const showPass = ref(false)

const error1 = ref(false)
const error2 = ref(false)
const errorMsg = ref('')
const errorMsg2 = ref('')

const checkAccount = async () => {
  error1.value = false
  try {
    const res = await fetch('http://localhost/IPPL_STROKE/api/auth.php', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'check_question', nama: name.value })
    })
    const result = await res.json()
    
    if (result.success) {
      question.value = result.pertanyaan
      step.value = 2
    } else {
      error1.value = true; errorMsg.value = result.message
    }
  } catch (err) { console.error(err) }
}

const resetPassword = async () => {
  error2.value = false
  try {
    const res = await fetch('http://localhost/IPPL_STROKE/api/auth.php', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'reset_password', nama: name.value, jawaban: answer.value, password_baru: newPassword.value })
    })
    const result = await res.json()
    
    if (result.success) {
      alert(result.message)
      emit('close')
    } else {
      error2.value = true; errorMsg2.value = result.message
    }
  } catch (err) { console.error(err) }
}
</script>