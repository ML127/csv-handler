<script setup>
import { ref } from 'vue'
import ArrowBottomPurple from '../../microComponents/arrowBottomPurple.vue'

const file = ref(null)
const result = ref(null)   // stores backend response
const errors = ref([])     // stores validation errors

function generateUploadMessage(data) {
  const inserted = data.employees_inserted ?? 0
  const duplicates = data.duplicates_skipped ?? 0
  const total = inserted + duplicates

  switch (true) {
    case inserted === 0 && duplicates > 0:
      return `All ${duplicates} rows were duplicates — nothing inserted.`
    case inserted > 0 && duplicates > 0:
      return `${inserted}/${total} rows inserted — ${duplicates} duplicates skipped.`
    case inserted > 0 && duplicates === 0:
      return `${inserted}/${total || inserted} rows inserted successfully!`
    default:
      return `No rows inserted. Check backend logs.`
  }
}

const uploadCsv = async () => {
  if (!file.value) {
    alert('No file selected.')
    return
  }

  const formData = new FormData()
  formData.append('csvFile', file.value)

  try {
    const response = await fetch('http://localhost:8888/api/employees', {
      method: 'POST',
      body: formData,
    })

    const data = await response.json()
    console.log('Upload response:', data)

    result.value = data
    errors.value = data.errors ?? []
  } catch (err) {
    console.error('Upload failed:', err)
    result.value = { error: 'Upload failed — check backend logs.' }
  }
}

const handleFileChange = (e) => {
  const selected = e.target.files?.[0]
  if (selected) file.value = selected
}
</script>

<template>
  <form class="uploadForm" @submit.prevent="uploadCsv">
    <div class="uploadContainer">
      <h2>Step 1:</h2>
      <label for="csvFile">Choose CSV file:</label>
      <input
          type="file"
          id="csvFile"
          name="csvFile"
          accept=".csv, text/csv"
          @change="handleFileChange"
      />
    </div>

    <ArrowBottomPurple />

    <h2>Step 2:</h2>
    <button type="submit">Upload</button>
  </form>

  <div v-if="result" class="summary">
    <h3>Upload Summary</h3>

    <div v-if="result.success">
      <p>{{ generateUploadMessage(result) }}</p>
      <p>Companies inserted: {{ result.companies_inserted ?? 0 }}</p>
      <p>Employees inserted: {{ result.employees_inserted ?? 0 }}</p>
      <p>Duplicates skipped: {{ result.duplicates_skipped ?? 0 }}</p>
    </div>

    <div v-else-if="result.error" class="error-message">
      <p>{{ result.error }}</p>
    </div>

    <div v-if="errors.length" class="error-box">
      <h4>Invalid Rows:</h4>
      <ul>
        <li v-for="(err, i) in errors" :key="i">{{ err }}</li>
      </ul>
    </div>
  </div>
</template>

<style scoped lang="scss">
.uploadForm {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  gap: 2.5vh;
  position: relative;

  @media (orientation: portrait){
    position: relative;
    right: 0;
  }
  .uploadContainer {
    display: flex;
    gap: 2vh;
    flex-direction: column;
    label {
      align-self: flex-start;
      font-size: 1rem;
      font-weight: bold;
    }
    input {
      font-size: 1rem;
    }
  }

  :deep(.arrowPurple) {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    left: 4rem;
  }

  button {
    height: auto;
    font-size: 1.1rem;
  }

  h2 {
    color: #6c63ff;
  }
}

.summary {
  margin-top: 2rem;
  padding: 1rem;
  background: #f9f9ff;
  border: 1px solid #ddd;
  border-radius: 8px;
  position: absolute;
  right: 10rem;
  width: 24rem;
  @media (orientation: portrait){
    position: absolute;
    top: 30rem;
    left: 1.5rem;
    width: 80vw;
  }
  p {
    margin: 0.3rem 0;
  }
}

.error-box {
  margin-top: 1rem;
  padding: 1rem;
  border: 1px solid #ff4d4f;
  border-radius: 8px;
  background: #fff1f0;
  color: #a8071a;

  ul {
    margin: 0.5rem 0 0;
    padding-left: 1.5rem;
  }
}

.error-message {
  color: #b71c1c;
}
</style>
