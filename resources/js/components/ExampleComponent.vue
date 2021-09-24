<template>
  <div class="center examplex">
    <vs-navbar
      color="#FFD561"
      square
      center-collapsed
      v-model="active"
    >
      <template #left>
        <box-icon
          type="solid"
          name="message-square"
          class="mr-2"
          color="#000"
        ></box-icon>
        <span class="brand-name">
            Opinions
        </span>
      </template>

      <template #right>
        <vs-button flat circle icon color="#FFD561">
          <box-icon name='chat' ></box-icon>
        </vs-button>
      </template>
    </vs-navbar>
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
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@800&display=swap');

    .brand-name {
        color: #000;
        font-family: "Open Sans";
        font-weight: 800;
        letter-spacing: -50;
    }
</style>
