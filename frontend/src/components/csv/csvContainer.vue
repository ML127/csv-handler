<script setup lang="js">
import csVForm from './csVForm.vue'

const emit = defineEmits(['csv-uploaded'])

const handleUploadSuccess = () => {
  emit('csv-uploaded')
}
</script>

<template>
  <csVForm @upload-success="handleUploadSuccess" />
</template>

<style scoped lang="scss">
.csvMainContainer {
  width: 100%;
  height: 40vh;
  position: relative;
}
</style>