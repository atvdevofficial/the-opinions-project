<template>
  <v-app>
    <v-main>
      <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>
export default {};
</script>

<style lang="scss">
$font-family: "Roboto Condensed", serif;
.v-application {
  .headline,
  [class*="display-"],
  [class*="text-"] {
    color: #000;
    font-family: $font-family, sans-serif !important;
  }
  font-family: $font-family, sans-serif !important;
}
</style>
