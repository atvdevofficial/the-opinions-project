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
            <v-form ref="formLogin">
              <v-text-field
                placeholder="Email"
                v-model="userCredentials.email"
                :rules="emailRules"
              ></v-text-field>
              <v-text-field
                placeholder="Password"
                type="password"
                v-model="userCredentials.password"
                :rules="[(v) => !!v || 'Please enter your password']"
              ></v-text-field>
            </v-form>
            <v-container
              class="text-caption text-center red--text font-italic"
              v-if="errorMessage"
            >
              {{ errorMessage }}
            </v-container>
          </v-card-text>

          <v-card-actions>
            <v-row>
              <v-btn
                block
                rounded
                depressed
                color="#FFD561"
                class="font-weight-black"
                :loading="isLoggingIn"
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
      isLoggingIn: false,
      invalidCombination: false,

      emailRules: [
        (v) => !!v || "Please enter your email",
        (v) => /.+@.+/.test(v) || "Email must be a valid one",
      ],

      userCredentials: {
        email: null,
        password: null,
      },

      errorMessage: null,
    };
  },
  methods: {
    login() {
      var formValid = this.$refs.formLogin.validate();

      if (formValid) {
        // Set isLoggingIn to true
        this.isLoggingIn = true;

        axios
          .post("/api/login", this.userCredentials)
          .then((response) => {
            var responseData = response.data;

            sessionStorage.setItem("authToken", responseData.token);
            sessionStorage.setItem("userRole", responseData.role);
            sessionStorage.setItem("userId", responseData.ids.user);
            sessionStorage.setItem("critiqueId", responseData.ids.critique);

            this.$router.push({ name: "feed" });
          })
          .catch((error) => {
            if (error.response.status == 401) {
              // If response code is 401
              this.errorMessage = error.response.data.message;
            } else {
              // Default error message for all response code except 401
              this.errorMessage = "Something went wrong. Please try again.";
            }
          })
          .finally((_) => {
            // Set isLoggingIn to false after api request
            this.isLoggingIn = false;
          });
      }
    },
  },
};
</script>
