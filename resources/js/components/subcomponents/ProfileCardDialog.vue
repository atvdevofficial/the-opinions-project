<template>
  <v-card rounded="lg" elevation="0">
    <!-- Image and Name -->
    <v-card-title>
      <v-row align="center" justify="center">
        <v-col cols="12" class="text-center">
          <v-avatar color="#FFEAB1" size="75">
            <box-icon name="user" size="md"></box-icon>
          </v-avatar>
        </v-col>
        <v-col cols="12">
          <div class="text-center">{{ profile.name }}</div>
          <div class="caption text-center">{{ profile.username }}</div>
        </v-col>
      </v-row>
    </v-card-title>

    <!-- Metrics -->
    <v-card-text>
      <v-row dense align="center" justify="center">
        <!-- Profile Metrics (Likes, Followers, Followings) -->
        <v-col
          cols="4"
          v-for="(metric, index) in profileMetrics"
          :key="index + 'pm1'"
        >
          <div class="text-center">{{ metric.value }}</div>
          <div class="caption text-center font-italic">{{ metric.name }}</div>
        </v-col>

        <!-- Profile Dialog -->
        <v-col cols="12" class="mt-2">
          <v-dialog persistent v-model="profileDialog" max-width="400px">
            <!-- Update Profile Button -->
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                x-small
                text
                block
                rounded
                depressed
                color="default"
                v-bind="attrs"
                v-on="on"
              >
                Update Profile
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <!-- Intentionaly left blank -->
              </v-card-title>

              <!-- Dialog Body -->
              <v-card-text>
                <v-form ref="profileForm">
                  <v-row dense align="center" justify="center">
                    <!-- Default Profile Image -->
                    <v-col cols="12" class="text-center">
                      <v-avatar color="#FFEAB1" size="100">
                        <box-icon name="user" size="md"></box-icon>
                      </v-avatar>
                    </v-col>

                    <v-col cols="12">
                      <div class="mt-2 px-4">
                        <v-btn
                          x-small
                          text
                          block
                          depressed
                          @click="seeSharedOpinions"
                          >See shared opinions</v-btn
                        >
                      </div>
                    </v-col>

                    <!-- Profile Metrics (Likes, Followers, Followings) -->
                    <v-col
                      class="mt-4 d-md-none"
                      cols="4"
                      v-for="(metric, index) in profileMetrics"
                      :key="index + 'pm2'"
                    >
                      <div class="text-center">{{ metric.value }}</div>
                      <div class="caption text-center font-italic">
                        {{ metric.name }}
                      </div>
                    </v-col>

                    <!-- Profile Dialog Divider -->
                    <v-col cols="12" class="mt-4 d-md-none">
                      <v-divider></v-divider>
                    </v-col>

                    <!-- Name text field -->
                    <v-col cols="12">
                      <v-text-field
                        placeholder="Name"
                        type="text"
                        v-model="editedProfile.name"
                        :rules="[(v) => !!v || 'Name is required']"
                        :error-messages="profileFormServerValidations.name"
                      ></v-text-field>
                    </v-col>

                    <!-- Username text field -->
                    <v-col cols="12">
                      <v-text-field
                        placeholder="Username"
                        type="text"
                        v-model="editedProfile.username"
                        :rules="[(v) => !!v || 'Username is required']"
                        :error-messages="profileFormServerValidations.username"
                      ></v-text-field>
                    </v-col>

                    <!-- Email text field -->
                    <v-col cols="12">
                      <v-text-field
                        placeholder="Email"
                        type="email"
                        v-model="editedProfile.email"
                        :rules="[(v) => !!v || 'Email is required']"
                        :error-messages="profileFormServerValidations.email"
                      ></v-text-field>
                    </v-col>

                    <!-- Password text field -->
                    <v-col cols="12">
                      <v-text-field
                        placeholder="Password"
                        type="password"
                        v-model="editedProfile.password"
                        :error-messages="profileFormServerValidations.password"
                      ></v-text-field>
                    </v-col>
                  </v-row>
                </v-form>
              </v-card-text>

              <!-- Dialog Actions -->
              <v-card-actions>
                <!-- Logout dialog -->
                <div class="d-flex d-md-none">
                  <logout-dialog
                    :showDialog="logoutDialog"
                    @close="logoutDialogClose"
                  ></logout-dialog>
                </div>

                <!-- Spacer -->
                <v-spacer></v-spacer>
                <!-- Dialog cancel button -->
                <v-btn text color="default" @click="profileDialogClose">
                  Cancel
                </v-btn>
                <!-- Dialog update button -->
                <v-btn
                  rounded
                  depressed
                  color="#FFD561"
                  class="font-weight-black px-8"
                  @click="updateCritiqueProfile"
                  :loading="isUpdatingProfile"
                >
                  Update
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import LogoutDialog from "./LogoutDialog.vue";

export default {
  name: "ProfileCardDialog",
  components: {
    LogoutDialog,
  },
  props: {
    showDialog: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isUpdatingProfile: false,
      profileDialog: false,
      logoutDialog: false,

      profile: {
        name: "Profile Name",
        username: "Profile Username",
        email: "Profile Email",
        password: null,
      },

      // Used for profile editing purposes
      editedProfile: {
        name: "Profile Name",
        username: "Profile Username",
        email: "Profile Email",
        password: null,
      },

      // Server validation for profile form
      profileFormServerValidations: {
        name: null,
        username: null,
        email: null,
        password: null,
      },

      profileMetrics: [
        { name: "Likes", value: 100 },
        { name: "Followers", value: 100 },
        { name: "Following", value: 100 },
      ],
    };
  },
  watch: {
    showDialog: function () {
      this.profileDialog = this.showDialog;
    },
  },
  mounted() {
    this.retrieveCritiqueProfile();
  },
  methods: {
    // Handler for profile dialog close
    profileDialogClose() {
      this.profileDialog = false;

      // Set editedProfile equals to profile without link
      this.editedProfile = Object.assign({}, this.profile);

      // Emit an event named close together
      // with profileDialog value
      this.$emit("close", this.profileDialog);
    },

    // Handler for logout dialog close
    logoutDialogClose(value) {
      this.logoutDialog = value;
    },

    // Retrieve current authenticated critique profile
    retrieveCritiqueProfile() {
      // Retrieve current authenticated crituque id from session storage
      var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

      axios
        .get("/api/critiques/" + critiqueId)
        .then((response) => {
          let data = response.data;

          // Extract needed critique profile info from response
          this.profile = {
            name: data.name,
            username: data.username,
            email: data.user.email,
          };

          // Set editedProfile equals to profile without link
          this.editedProfile = Object.assign({}, this.profile);
        })
        .catch((error) => {
          // Pop Notification
          toastr.error(
            "A problem occured while processing your request. Please try again.",
            "Something Went Wrong",
            { timeOut: 2000 }
          );
        })
        .finally((_) => {});
    },

    // Update current authenticated critique profile
    updateCritiqueProfile() {
      // Validate form
      if (this.$refs.profileForm.validate()) {
        // Retrieve current authenticated crituque id from session storage
        var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

        // set isUpdatingProfile to true
        this.isUpdatingProfile = true;

        axios
          .put("/api/critiques/" + critiqueId, this.editedProfile)
          .then((response) => {
            let data = response.data;

            // Extract needed updated critique profile info from response
            this.profile = {
              name: data.name,
              username: data.username,
              email: data.user.email,
            };

            // Close profile dialog
            this.profileDialogClose();

            // Pop Notification
            toastr.success(
              "Your profile was updated successfuly",
              "Critique Profile Updated",
              { timeOut: 2000 }
            );
          })
          .catch((error) => {
            // Server validations
            if (error.response.status == 422) {
              this.profileFormServerValidations = {
                ...error.response.data.errors,
              };
            } else {
              // Default action
              // Pop Notification
              toastr.error(
                "A problem occured while processing your request. Please try again.",
                "Something Went Wrong",
                { timeOut: 2000 }
              );
            }
          })
          .finally((_) => {
            // Set isUpdatingProfile to false
            // at the end of the request
            this.isUpdatingProfile = false;
          });
      }
    },

    // Emit an event
    seeSharedOpinions() {
      this.$emit("shared-opinions");

      this.profileDialog = false;
      // Emit an event named close together
      // with profileDialog value
      this.$emit("close", this.profileDialog);
    },
  },
};
</script>
