<script setup lang="js">
const props = defineProps({
  company: String,
  avgSalary: [Number, String]
})
</script>

<template>
  <div class="company-card">
    <h3 class="company-name">{{ company }}</h3>
    <p class="avg-salary">Average Salary: £{{ avgSalary }}</p>
  </div>
</template>

<style scoped lang="scss">
.company-card {
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
  .company-name {
    font-size: 1.4rem;
    color: #6c63ff;
    font-weight: bold;
    margin-bottom: 0.5rem;
  }

  .avg-salary {
    font-size: 1.1rem;
    font-weight: 500;
    color: #333;
  }
}
</style>
