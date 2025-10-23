<script setup>
import { ref, onMounted, defineExpose } from 'vue'
import employeesEntry from './employeesEntry.vue'

const employees = ref([])

const fetchEmployees = async () => {
  try {
    const res = await fetch('http://localhost:8888/api/employees')
    const data = await res.json()
    if (data.success && Array.isArray(data.employees)) {
      employees.value = data.employees
    }
  } catch (err) {
    console.error('Failed to fetch employees:', err)
  }
}

onMounted(fetchEmployees)
defineExpose({ fetchEmployees })
</script>

<template>
  <section class="employeesContainer">
    <h2>Employees</h2>
    <div v-if="!employees.length" class="empty">No employees yet.</div>
    <div v-else class="employeesGrid">
      <employeesEntry
          v-for="emp in employees"
          :key="emp.id"
          :id="emp.id"
          :name="emp.employee_name"
          :email="emp.email"
          :company="emp.company"
          :salary="emp.salary"
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
    @media (orientation: portrait){
      margin-left: 0;
      width: 100%;
      text-align: center;
    }
  }

  .employeesGrid {
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
}
</style>
