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
$app-bg: #ffffff;          // white background
$primary-color: #6C63FF;   // example accent color
$text-color: #333;

.app {
  min-height: 100vh;
  background-color: $app-bg;
  color: $text-color;
  font-family: 'Helvetica Neue', Arial, sans-serif;

  header {
    background-color: $primary-color;
    color: white;
    text-align: center;
    padding: 1rem;

    h1 {
      margin: 0;
      font-size: 1.8rem;
    }
  }

  main {
    max-width: 900px;
    margin: 2rem auto;
    padding: 1rem;
    text-align: center;
  }
}

/* Responsive tweaks */
@media (max-width: 600px) {
  header h1 {
    font-size: 1.4rem;
  }

  main {
    padding: 0.5rem;
  }
}
</style>
