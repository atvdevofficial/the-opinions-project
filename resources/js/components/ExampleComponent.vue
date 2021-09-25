<template>
  <div>
    <v-btn elevation="2">Hello World</v-btn>
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
