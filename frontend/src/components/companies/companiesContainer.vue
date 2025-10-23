<script setup>
import { ref, onMounted, defineExpose } from 'vue'
import companiesEntry from './companiesEntry.vue'

const averages = ref([])

const fetchCompanyAverages = async () => {
  try {
    const res = await fetch('http://localhost:8888/api/companies/averages')
    const data = await res.json()

    // backend returns { success, averages: [...] }
    if (data.success && Array.isArray(data.averages)) {
      averages.value = data.averages
    } else {
      averages.value = []
      console.warn('Unexpected averages response:', data)
    }
  } catch (err) {
    console.error('Failed to fetch averages:', err)
  }
}

defineExpose({ fetchCompanyAverages })

onMounted(fetchCompanyAverages)
</script>

<template>
  <section class="companiesContainer">
    <h2>Company Average Salaries</h2>

    <div v-if="!averages.length" class="empty">
      <p>No data yet. Upload a CSV file to populate company averages.</p>
    </div>

    <div v-else class="companiesGrid">
      <companiesEntry
          v-for="(company, idx) in averages"
          :key="idx"
          :company="company.company"
          :average="company.avg_salary"
      />
    </div>
  </section>
</template>

<style scoped lang="scss">
.companiesContainer {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  width: 100%;
  min-height: 50vh;

  h2 {
    color: #6c63ff;
    align-self: flex-start;
    margin-left: 6rem;
    @media(orientation: portrait){
      margin-left: 0;
      width: 100%;
      text-align: center;
    }
  }

  .empty {
    color: #777;
    padding: 2rem;
  }

  .companiesGrid {
    display: grid;
    width: 75.5rem;
    background: #6c63ff;
    padding: 2rem;
    border-radius: 50px;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;

    @media (orientation: portrait) {
      grid-template-columns: repeat(1, 1fr);
      width: 90%;
    }
  }
}
</style>
