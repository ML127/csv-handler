<script setup lang="js">

</script>

<template>
  <form action="/" method="post" enctype="multipart/form-data">
    <label for="csvFile">Choose CSV file:</label>
    <input type="file" id="csvFile" name="csvFile" accept=".csv, text/csv">
    <button type="submit">Upload</button>
  </form>
</template>

<style scoped lang="scss">

</style>