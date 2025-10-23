<script setup>
import { ref, watch } from 'vue'
const apiBaseUrl = import.meta.env.VITE_API_BASE_URL;
const emit = defineEmits(['email-updated'])

const props = defineProps({
  id: { type: Number, required: true },
  name: String,
  email: String,
  company: String,
  salary: [String, Number]
})

const editing = ref(false)
const newEmail = ref(props.email)
const statusMessage = ref('')
const statusType = ref('') // 'success' | 'error'

// sync new email if parent updates
watch(() => props.email, (val) => {
  if (!editing.value) newEmail.value = val
})

const validateEmail = (email) => {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return regex.test(email)
}

const updateEmail = async () => {
  statusMessage.value = ''
  statusType.value = ''

  if (!newEmail.value || newEmail.value === props.email) {
    statusMessage.value = 'No changes to save.'
    statusType.value = 'error'
    editing.value = false
    return
  }

  if (!validateEmail(newEmail.value)) {
    statusMessage.value = 'Invalid email format.'
    statusType.value = 'error'
    return
  }

  try {
    const res = await fetch(`${apiBaseUrl}/employees/${props.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: newEmail.value })
    })

    const data = await res.json()

    if (res.ok) {
      statusMessage.value = 'Email updated successfully!'
      statusType.value = 'success'
      editing.value = false
      emit('email-updated', { id: props.id, email: newEmail.value })
    } else {
      statusMessage.value = data.error || 'Error updating email.'
      statusType.value = 'error'
    }
  } catch (err) {
    console.error('Update failed:', err)
    statusMessage.value = 'Failed to update email — check backend logs.'
    statusType.value = 'error'
  }

  // clear message after 3s
  setTimeout(() => {
    statusMessage.value = ''
    statusType.value = ''
  }, 3000)
}
</script>

<template>
  <div class="employee-details">
    <span class="name">{{ name }}</span>

    <div class="email">
      <span v-if="!editing">{{ email }}</span>
      <input
          v-else
          v-model="newEmail"
          type="email"
          placeholder="Enter new email"
      />

      <div class="buttonGroup">
        <button @click="editing = !editing">
          {{ editing ? 'Cancel' : 'Edit' }}
        </button>
        <button v-if="editing" @click="updateEmail">Save</button>
      </div>
    </div>

    <span class="company">{{ company }}</span>
    <span class="salary">{{ '$' + salary }}</span>

    <p v-if="statusMessage" :class="['status', statusType]">{{ statusMessage }}</p>
  </div>
</template>

<style scoped>
.employee-details {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding: 2rem 5rem;
  background: white;
  border-radius: 2.5rem;
  @media (orientation: portrait) {
    padding: 0.5rem 2rem;
    width: 80%;
  }
  .email {
    display: flex;
    justify-content: space-between;
    align-items: center;

    input {
      width: 100%;
      font-size: 1rem;
      font-weight: bold;
      margin-right: 0.5rem;
    }
  }

  .buttonGroup {
    display: flex;
    gap: 0.5rem;
    align-items: center;

    button {
      background: #6c63ff;
      color: white;
      padding: 0.5rem;
    }
  }

  .status {
    font-size: 0.9rem;
    font-weight: bold;
    margin-top: 0.5rem;
  }

  .status.success {
    color: #28a745;
  }

  .status.error {
    color: #ff4d4f;
  }
}
</style>
