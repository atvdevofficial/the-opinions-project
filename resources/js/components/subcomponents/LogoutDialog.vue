<template>
  <v-dialog persistent v-model="logoutDialog" max-width="350px">
    <template v-slot:activator="{ on, attrs }">
      <v-btn icon v-bind="attrs" v-on="on">
        <box-icon name="log-out-circle"></box-icon>
      </v-btn>
    </template>
    <v-card>
      <!-- Card title -->
      <v-card-title> </v-card-title>

      <!-- Card body -->
      <v-card-text>
        <v-container class="text-center">
          You are about to be logged out. Please confirm.
        </v-container>
      </v-card-text>

      <!-- Card actions -->
      <v-card-actions>
        <v-spacer></v-spacer>
        <!-- Cancel button -->
        <v-btn color="default" text @click="closeDialog"> Cancel </v-btn>

        <!-- Share button -->
        <v-btn
          rounded
          depressed
          color="#FFD561"
          class="font-weight-black px-8"
          @click="logout"
        >
          Confirm
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "LogoutDialog",
  props: {
    showDialog: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      logoutDialog: false,
    };
  },
  watch: {
    showDialog: function () {
      this.logoutDialog = this.showDialog;
    },
  },
  methods: {
    closeDialog() {
      this.logoutDialog = false;
      this.$emit("close", this.logoutDialog);
    },

    logout() {
      axios
        .post("/api/logout")
        .then((response) => {})
        .catch((error) => {})
        .finally((_) => {
          // Clear session storage
          sessionStorage.clear();
          this.$router.push({ name: "login" });
        });
    },
  },
};
</script>
