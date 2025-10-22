<script setup lang="js">
import ArrowBottomPurple from '../../microComponents/arrowBottomPurple.vue'
</script>

<template>
  <form action="/" class="uploadForm" method="post" enctype="multipart/form-data">
    <div class="uploadContainer">
      <h2>Step 1:</h2>
      <label for="csvFile">Choose CSV file:</label>
      <input type="file" id="csvFile" name="csvFile" accept=".csv, text/csv">
    </div>
    <ArrowBottomPurple></ArrowBottomPurple>
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

  h2{
    color: #6C63FF;
    size: 2rem;
  }
}

</style>