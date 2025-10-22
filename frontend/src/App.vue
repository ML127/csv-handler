<template>
  <div class="app">
    <header>
      <h1>CSV Handler</h1>
    </header>
    <main>
      <section class="csvUpload">
        <csvContainer></csvContainer>
      </section>
      <!-- Components like UploadCsv and EmployeeList can go here -->
    </main>
  </div>
</template>


<script setup>
import csvContainer from './components/csv/csvContainer.vue'
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
      padding: 5vh 0;
      height: 40vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
    }
  }

}

h2{
  color: #6C63FF;
}

@media (max-width: 600px) {



}
</style>
