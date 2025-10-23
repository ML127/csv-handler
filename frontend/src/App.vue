<script setup>
import { ref } from 'vue'
import csvContainer from './components/csv/csvContainer.vue'
import employeesContainer from './components/employees/employeesContainer.vue'
import companiesContainer from './components/companies/companiesContainer.vue'

const employeesRef = ref(null)
const companiesRef = ref(null)

const refreshData = async () => {
  await Promise.all([
    employeesRef.value?.fetchEmployees?.(),
    companiesRef.value?.fetchCompanyAverages?.()
  ])
}
</script>

<template>
  <div class="app">
    <header><h1>CSV Upload Handler</h1></header>

    <main>
      <section class="csvUpload">
        <csvContainer @csv-uploaded="refreshData" />
      </section>

      <section class="employeesMainContainer">
        <employeesContainer ref="employeesRef" />
      </section>

      <section class="companiesMainContainer">
        <companiesContainer ref="companiesRef" />
      </section>
    </main>
  </div>
</template>

<style scoped lang="scss">
.app {
  min-height: 100vh;
  background-color: white;
  font-family: 'Helvetica Neue', Arial, sans-serif;

  header {
    background-color: #6C63FF;
    color: white;
    text-align: center;
    padding: 1rem;

    h1 {
      margin: 0;
      font-size: 1.8rem;
    }
  }

  main {
    .csvUpload {
      width: 100%;
      margin: auto;
      padding: 10vh 0;
      height: 40vh;
      display: flex;
      max-width: 1400px;
      justify-content: center;
      align-items: center;

      @media (orientation: portrait) {
        align-items: flex-start;
        height: 71vh;
      }
    }

    .employeesMainContainer,
    .companiesMainContainer {
      width: 100%;
      margin: auto;
      padding: 10vh 0;
      display: flex;
      max-width: 1400px;
      justify-content: center;
      align-items: flex-start;
    }

    .companiesMainContainer {
      padding-top: 0;
    }
  }
}

h2 {
  color: #6C63FF;
}
</style>
