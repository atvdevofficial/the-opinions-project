<template>
  <v-container style="height: 100vh">
    <v-row align="center" justify="center" style="height: 100vh">
      <v-col cols="12" sm="6" lg="4">
        <v-card elevation="0">
          <v-card-title class="font-italic title">
            It's time to let the world know about your opinions, share it
            now!</v-card-title
          >

          <v-card-text>
            <v-text-field placeholder="Username"></v-text-field>
            <v-text-field placeholder="Password" type="password"></v-text-field>
          </v-card-text>

          <v-card-actions>
            <v-row>
              <v-btn
                block
                rounded
                depressed
                color="#FFD561"
                class="font-weight-black"
                @click="login"
              >
                Login
              </v-btn>
              <v-btn
                x-small
                text
                block
                rounded
                depressed
                color="default"
                class="mt-2"
              >
                Create an Account
              </v-btn>
              <v-container
                class="text-caption text-center red white--text mt-4"
                v-if="invalidCombination == true"
              >
                Username and Password combination does'nt exists
              </v-container>
            </v-row>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "LoginComponent",
  data() {
    return {
      invalidCombination: false,
    };
  },
  methods: {
    login() {
      this.$router.push({ name: "feed" });
    },
  },
};
</script>
