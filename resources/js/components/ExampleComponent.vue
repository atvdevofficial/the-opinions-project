<template>
  <div>
    <span class="brand-name">Opinions</span>
  </div>
</template>

<script>
export default {
  data() {
    return {
      active: null,
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@800&display=swap");

.brand-name {
  color: #000;
  font-family: "Open Sans";
  font-weight: 800;
  letter-spacing: -50;
}
</style>
