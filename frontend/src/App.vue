<template>
  <div class="app">
    <header>
      <h1>CSV Upload Handler</h1>
    </header>
    <main>
      <section class="csvUpload">
        <csvContainer></csvContainer>
      </section>
      <section class="employeesMainContainer">
        <employeesContainer></employeesContainer>
      </section>
      <section class="companiesMainContainer">
        <companiesContainer></companiesContainer>
      </section>
    </main>
  </div>
</template>


<script setup>
import csvContainer from './components/csv/csvContainer.vue'
import employeesContainer from './components/employees/employeesContainer.vue'
import companiesContainer from './components/companies/companiesContainer.vue'
</script>

<style lang="scss" scoped>



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
  main{
    .csvUpload{
      width: 100%;
      margin-left: auto;
      margin-right: auto;
      padding: 10vh 0;
      height: 40vh;
      display: flex;
      max-width: 1400px;
      justify-content: center;
      align-items: center;
      position: relative;
      @media(orientation: portrait){
        align-items: flex-start;
        height: 71vh;
      }
    }
    .employeesMainContainer, .companiesMainContainer{
      width: 100%;
      margin-left: auto;
      margin-right: auto;
      padding: 10vh 0;
      height: auto;
      display: flex;
      max-width: 1400px;
      justify-content: center;
      align-items: flex-start;
      position: relative;
    }
    .companiesMainContainer{
      @media(orientation: portrait){
        padding-bottom: 0;
      }
    }
  }

}

h2{
  color: #6C63FF;
}

@media (max-width: 600px) {



}
</style>
