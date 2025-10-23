<script setup>
const props = defineProps({
  company: String,
  average: [String, Number]
})
</script>

<template>
  <div class="companyCard">
    <span class="company">{{ company }}</span>
    <span class="average">£{{ average }}</span>
  </div>
  </template>
<style scoped lang="scss">
.companyCard {
  background: white;
  border-radius: 2rem;
  padding: 1.5rem 2rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
  @media (orientation: portrait) {
    padding: 0.5rem 2rem;
    width: 80%;
  }
  .company{
    font-size: 1.4rem;
    color: #6c63ff;
    font-weight: bold;
    margin-bottom: 0.5rem;
  }

  .average {
    font-size: 1.1rem;
    font-weight: 500;
    color: #333;
  }
}
</style>
