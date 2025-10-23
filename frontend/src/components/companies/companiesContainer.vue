<script setup lang="js">
import { ref, onMounted } from 'vue'
import companiesEntry from './companiesEntry.vue'

const companies = ref([])

const fetchCompanyAverages = async () => {
  try {
    const res = await fetch('http://localhost:8888/api/companies/averages')
    const data = await res.json()

    if (data.success && Array.isArray(data.averages)) {
      companies.value = data.averages
    } else {
      console.warn('Invalid response:', data)
    }
  } catch (err) {
    console.error('Failed to fetch company averages:', err)
  }
}

onMounted(fetchCompanyAverages)
</script>

<template>
  <section class="employeesContainer">
    <h2>Companies Averages</h2>

    <div v-if="companies.length === 0" class="empty-state">
      <p>No company data found yet. Upload a CSV to generate averages!</p>
    </div>

    <div v-else class="companiesGrid">
      <companiesEntry
          v-for="(comp, index) in companies"
          :key="index"
          :company="comp.company"
          :avg-salary="comp.avg_salary"
      />
    </div>
  </section>
</template>

<style scoped lang="scss">
.employeesContainer {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  width: 100%;

  h2 {
    margin-left: 6rem;
    color: #6c63ff;
    align-self: flex-start;
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
      width: 100%;
      border-radius: unset;
      place-items: center;
    }
  }

  .empty-state {
    margin-top: 3rem;
    text-align: center;
    color: #666;
  }
}
</style>
