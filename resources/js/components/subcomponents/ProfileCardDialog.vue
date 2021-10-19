<template>
  <v-card rounded="lg" elevation="0">
    <!-- Image and Name -->
    <v-card-title>
      <v-row align="center" justify="center">
        <v-col cols="4">
          <v-avatar color="#FFEAB1" size="75">
            <box-icon name="user" size="md"></box-icon>
          </v-avatar>
        </v-col>
        <v-col cols="8">
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
                <v-row dense align="center" justify="center">
                  <!-- Default Profile Image -->
                  <v-col cols="12" class="text-center">
                    <v-avatar color="#FFEAB1" size="100">
                      <box-icon name="user" size="md"></box-icon>
                    </v-avatar>
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

                  <!-- Form textfields -->
                  <v-col
                    cols="12"
                    v-for="(formTextField, index) in formTextFields"
                    :key="index + 'ftf'"
                  >
                    <v-text-field
                      :placeholder="formTextField.placeholder"
                      :type="formTextField.type"
                      :v-model="formTextField.model"
                    ></v-text-field>
                  </v-col>
                </v-row>
              </v-card-text>

              <!-- Dialog Actions -->
              <v-card-actions>
                <v-spacer></v-spacer>
                <!-- Dialog cancel button -->
                <v-btn text color="default" @click="closeDialog">
                  Cancel
                </v-btn>
                <!-- Dialog update button -->
                <v-btn
                  rounded
                  depressed
                  color="#FFD561"
                  class="font-weight-black px-8"
                  @click="closeDialog"
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
export default {
  name: "ProfileCardDialog",
  props: {
    showDialog: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      profileDialog: false,

      profile: {
        name: "Profile Name",
        username: "Profile Username",
        password: null,
      },

      formTextFields: [
        { placeholder: "Name", type: "text", model: "profile.name" },
        { placeholder: "Username", type: "text", model: "profile.username" },
        {
          placeholder: "Password",
          type: "password",
          model: "profile.password",
        },
      ],

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
  methods: {
    closeDialog() {
      this.profileDialog = false;
      this.$emit("close", this.profileDialog);
    },
  },
};
</script>
