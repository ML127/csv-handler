<script setup>
import { ref } from 'vue'
import ArrowBottomPurple from '../../microComponents/arrowBottomPurple.vue'

const file = ref(null)

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
    const response = await fetch('http://localhost:8888/api/upload', {
      method: 'POST',
      body: formData,
    })

    const data = await response.json()
    console.log('Upload response:', data)

    const message = generateUploadMessage(data)
    alert(message)
  } catch (err) {
    console.error('Upload failed:', err)
    alert ('Upload failed check backend logs.')
  }
}

const handleFileChange = (e) => {
  const selected = e.target.files?.[0]
  if (selected) {
    file.value = selected
  }
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
</template>

<style scoped lang="scss">

.uploadForm{
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  gap: 2.5vh;
  .uploadContainer{
    display: flex;
    gap: 2vh;
    flex-direction: column;
    label{
      align-self: flex-start;
      font-size: 1rem;
      font-weight: bold;
    }
    input{

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

  button{



    height: auto;
    font-size: 1.1rem;
  }

  h2{
    color: #6C63FF;
    size: 2rem;
  }
}

</style>