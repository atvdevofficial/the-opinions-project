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
        <v-col cols="4">
          <div class="text-center">{{ profileStatisctics.topics }}</div>
          <div class="caption text-center font-italic">Topics</div>
        </v-col>

        <v-col
          cols="4"
          @click="followersDialog = true"
          style="cursor: pointer !important"
        >
          <div class="text-center">{{ profileStatisctics.followers }}</div>
          <div class="caption text-center font-italic">Followers</div>
          <v-dialog v-model="followersDialog" max-width="400px">
            <v-card>
              <v-card-title>Followers</v-card-title>
              <v-card-text>
                <div
                  v-if="profileFollowersAndFollowings.followers.data.length > 0"
                >
                  <v-card
                    elevation="0"
                    v-for="(follower, index) in profileFollowersAndFollowings
                      .followers.data"
                    :key="index"
                  >
                    <v-card-title class="px-2 py-0">
                      <v-list-item class="grow px-0">
                        <v-list-item-avatar color="#FFEAB1">
                          <box-icon name="user" size="sm"></box-icon>
                        </v-list-item-avatar>

                        <v-list-item-content>
                          <v-list-item-title>
                            <div>{{ follower.name }}</div>
                            <div class="caption font-italic">
                              {{ follower.username }}
                            </div>
                          </v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-card-title>
                  </v-card>
                  <v-btn
                    block
                    small
                    text
                    depressed
                    @click="loadMoreFollowersAndFollowings('followers')"
                    v-if="profileFollowersAndFollowings.followers.next_page_url"
                    :loading="isLoadingMoreFollowersAndFollowings"
                  >
                    Load more followers
                  </v-btn>
                </div>
                <div v-else class="text-center font-italic">
                  You do not have any followers
                </div>
              </v-card-text>
            </v-card>
          </v-dialog>
        </v-col>

        <v-col
          cols="4"
          @click="followingsDialog = true"
          style="cursor: pointer !important"
        >
          <div class="text-center">{{ profileStatisctics.followings }}</div>
          <div class="caption text-center font-italic">Followings</div>
          <v-dialog v-model="followingsDialog" max-width="400px">
            <v-card>
              <v-card-title>Followings</v-card-title>
              <v-card-text>
                <div
                  v-if="
                    profileFollowersAndFollowings.followings.data.length > 0
                  "
                >
                  <v-card
                    elevation="0"
                    v-for="(following, index) in profileFollowersAndFollowings
                      .followings.data"
                    :key="index"
                  >
                    <v-card-title class="px-2 py-0">
                      <v-list-item class="grow px-0">
                        <v-list-item-avatar color="#FFEAB1">
                          <box-icon name="user" size="sm"></box-icon>
                        </v-list-item-avatar>

                        <v-list-item-content>
                          <v-list-item-title>
                            <div>{{ following.name }}</div>
                            <div class="caption font-italic">
                              {{ following.username }}
                            </div>
                          </v-list-item-title>
                        </v-list-item-content>

                        <v-list-item-action>
                          <v-btn
                            small
                            depressed
                            color="default"
                            class="font-weight-bold"
                            @click="unfollowCritique(following.id, index)"
                          >
                            Unfollow
                          </v-btn>
                        </v-list-item-action>
                      </v-list-item>
                    </v-card-title>
                  </v-card>
                  <v-btn
                    block
                    small
                    text
                    depressed
                    @click="loadMoreFollowersAndFollowings('followings')"
                    v-if="
                      profileFollowersAndFollowings.followings.next_page_url
                    "
                    :loading="isLoadingMoreFollowersAndFollowings"
                  >
                    Load more followings
                  </v-btn>
                </div>
                <div v-else class="text-center font-italic">
                  You do not follow anybody
                </div>
              </v-card-text>
            </v-card>
          </v-dialog>
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

                    <v-col cols="6">
                      <div class="mt-2 px-4">
                        <v-btn
                          small
                          text
                          block
                          depressed
                          @click="seeSharedOpinions"
                          >Shared opinions</v-btn
                        >
                      </div>
                    </v-col>

                    <v-col cols="6">
                      <div class="mt-2 px-4">
                        <v-btn
                          small
                          text
                          block
                          depressed
                          @click="followedTopicsDialog = true"
                          >Followed Topics</v-btn
                        >
                      </div>
                    </v-col>

                    <!-- Profile Metrics (Likes, Followers, Followings) -->

                    <v-col class="mt-4 d-md-none" cols="4">
                      <div class="text-center">
                        {{ profileStatisctics.topics }}
                      </div>
                      <div class="caption text-center font-italic">
                        Topics Followed
                      </div>
                    </v-col>

                    <v-col
                      class="mt-4 d-md-none"
                      cols="4"
                      @click="followersDialog = true"
                      style="cursor: pointer !important"
                    >
                      <div class="text-center">
                        {{ profileStatisctics.followers }}
                      </div>
                      <div class="caption text-center font-italic">
                        Followers
                      </div>
                    </v-col>

                    <v-col
                      class="mt-4 d-md-none"
                      cols="4"
                      @click="followingsDialog = true"
                      style="cursor: pointer !important"
                    >
                      <div class="text-center">
                        {{ profileStatisctics.followings }}
                      </div>
                      <div class="caption text-center font-italic">
                        Followings
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
                        :disabled="isRetrievingProfile"
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
                        :disabled="isRetrievingProfile"
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
                        :disabled="isRetrievingProfile"
                      ></v-text-field>
                    </v-col>

                    <!-- Password text field -->
                    <v-col cols="12">
                      <v-text-field
                        placeholder="Password"
                        type="password"
                        v-model="editedProfile.password"
                        :error-messages="profileFormServerValidations.password"
                        :disabled="isRetrievingProfile"
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

        <!-- Shared opinions button -->
        <v-col cols="6">
          <v-btn x-small text block depressed @click="seeSharedOpinions"
            >Shared opinions</v-btn
          >
        </v-col>

        <!-- Followed topics button -->
        <v-col cols="6">
          <v-dialog v-model="followedTopicsDialog" max-width="400px">
            <!-- Followed topics Button -->
            <template v-slot:activator="{ on, attrs }">
              <v-btn x-small text block depressed v-bind="attrs" v-on="on">
                Followed Topics</v-btn
              >
            </template>
            <v-card>
              <v-card-title> Followed Topics </v-card-title>

              <!-- Dialog Body -->
              <v-card-text>
                <v-form ref="followedTopicsForm">
                  <!-- Topics select field -->
                  <v-autocomplete
                    multiple
                    item-text="text"
                    item-value="id"
                    :items="topics"
                    placeholder="Select topics you want to follow"
                    :loading="isRetrievingTopics"
                    :disabled="isRetrievingTopics || isUpdatingFollowedTopics"
                    :rules="[(v) => !!v.length || 'Follow atleast one topic']"
                    v-model="followedTopics"
                  >
                    <template v-slot:item="data">
                      <v-list-item-content>
                        <v-list-item-title
                          v-html="data.item.text"
                          class="black--text"
                        ></v-list-item-title>
                      </v-list-item-content>
                    </template>

                    <template #selection="{ item }">
                      <v-chip small color="#FFD561" class="caption">{{
                        item.text
                      }}</v-chip>
                    </template>
                  </v-autocomplete>
                </v-form>
              </v-card-text>

              <!-- Dialog Actions -->
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  text
                  color="default"
                  @click="followedTopicsDialog = false"
                >
                  Cancel
                </v-btn>
                <v-btn
                  rounded
                  depressed
                  color="#FFD561"
                  class="font-weight-black px-8"
                  @click="updateFollowedTopics"
                  :loading="isUpdatingFollowedTopics"
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
      isRetrievingProfile: false,
      isRetrievingTopics: false,
      isRetrievingCritiqueStatistics: false,
      isRetrievingFollowersAndFollowings: false,
      isLoadingMoreFollowersAndFollowings: false,
      isUnfollowingCritique: false,
      isUpdatingProfile: false,
      isUpdatingFollowedTopics: false,

      followersDialog: false,
      followingsDialog: false,
      profileDialog: false,
      logoutDialog: false,
      followedTopicsDialog: false,

      profile: {
        name: "Profile Name",
        username: "Profile Username",
        email: "Profile Email",
        password: null,
      },

      profileFollowersAndFollowings: {
        followers: { data: [], next_page_url: null },
        followings: { data: [], next_page_url: null },
      },

      profileStatisctics: {
        topics: 0,
        followers: 0,
        followings: 0,
      },

      topics: [],
      followedTopics: [],

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
    };
  },
  watch: {
    showDialog: function () {
      this.profileDialog = this.showDialog;
    },
  },
  mounted() {
    this.retrieveCritiqueProfile();
    this.retrieveTopicsAndFollowedTopics();
    this.retrieveCritiqueFollowersAndFollowings();
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
      // Set isRetrievingProfile to true
      this.isRetrievingProfile = true;

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

          this.profileStatisctics = data.statistics;

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
        .finally((_) => {
          this.isRetrievingProfile = false;
        });
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

    // Retrieve followed topics by critique
    retrieveTopicsAndFollowedTopics() {
      // Set isRetrievingTopics to true
      this.isRetrievingTopics = true;

      // Retrieve topics
      axios
        .get("/api/topics")
        .then((response) => {
          let data = response.data;

          this.topics = data;

          // Retrieve current authenticated crituque id from session storage
          var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

          // Retrieve followed topics
          axios
            .get(`/api/critiques/${critiqueId}/follows/topics`)
            .then((response) => {
              let data = response.data;

              this.followedTopics = data.map((a) => a.id);
            })
            .catch((error) => {
              // Pop Notification
              toastr.error(
                "A problem occured while processing your request. Please try again.",
                "Something Went Wrong",
                { timeOut: 2000 }
              );
            });
        })
        .catch((error) => {
          // Pop Notification
          toastr.error(
            "A problem occured while processing your request. Please try again.",
            "Something Went Wrong",
            { timeOut: 2000 }
          );
        })
        .finally((_) => {
          this.isRetrievingTopics = false;
        });
    },

    // Update followed topics
    updateFollowedTopics() {
      if (this.$refs.followedTopicsForm.validate()) {
        // Set isUpdatingFollowedTopics to true
        this.isUpdatingFollowedTopics = true;

        // Retrieve current authenticated crituque id from session storage
        var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

        // Retrieve followed topics
        axios
          .put(`/api/critiques/${critiqueId}/follows/topics`, {
            topics: this.followedTopics,
          })
          .then((response) => {
            // Pop Notification
            toastr.success(
              "You have successfuly update your followed topics",
              "Followed Topics Updated",
              { timeOut: 2000 }
            );

            // Close followed topics dialog
            this.followedTopicsDialog = false;

            // Recall retrieveTopicsAndFollowedTopics
            this.retrieveTopicsAndFollowedTopics();

            // Recall retrieveCritiqueProfile
            this.retrieveCritiqueProfile();
          })
          .catch((error) => {
            // Pop Notification
            toastr.error(
              "A problem occured while processing your request. Please try again.",
              "Something Went Wrong",
              { timeOut: 2000 }
            );
          })
          .finally((_) => {
            this.isUpdatingFollowedTopics = false;
          });
      }
    },

    retrieveCritiqueFollowersAndFollowings() {
      this.isRetrievingFollowersAndFollowings = true;

      axios
        .get("/api/critiques/follows/critiques")
        .then((response) => {
          const data = response.data;

          this.profileFollowersAndFollowings = data;
        })
        .catch((error) => {
          // Pop Notificatio    n
          toastr.error(
            "A problem occured while processing your request. Please try again.",
            "Something Went Wrong",
            { timeOut: 2000 }
          );
        })
        .finally((_) => {
          this.isRetrievingFollowersAndFollowings = false;
        });
    },

    unfollowCritique(critiqueId, index) {
      // Set isFollowingUnfollowingCritique to true
      this.isUnfollowingCritique = true;

      axios
        .put(`/api/critiques/follows/critiques/${critiqueId}/unfollow`)
        .then((response) => {
          //  this.profileFollowersAndFollowings.followings.data.slice(index, 1);
          this.$delete(
            this.profileFollowersAndFollowings.followings.data,
            index
          );
          this.profileStatisctics.followings--;
        })
        .catch((error) => {
          console.log(error);
          // Pop Notification
          toastr.error(
            "A problem occured while processing your request. Please try again.",
            "Something Went Wrong",
            { timeOut: 2000 }
          );
        })
        .finally((_) => {
          this.isUnfollowingCritique = false;
        });
    },

    loadMoreFollowersAndFollowings(type) {
      // Set isLoadingMoreFollowersAndFollowings to true
      this.isLoadingMoreFollowersAndFollowings = true;

      axios
        .get(this.profileFollowersAndFollowings[type].next_page_url)
        .then((response) => {
          let data = response.data;

          // Concat opinions
          this.profileFollowersAndFollowings[type].data =
            this.profileFollowersAndFollowings[type].data.concat(
              data[type].data
            );
          this.profileFollowersAndFollowings[type].next_page_url =
            data[type].next_page_url;
        })
        .catch((error) => {
          // Pop Notification
          toastr.error(
            "A problem occured while processing your request. Please try again.",
            "Something Went Wrong",
            { timeOut: 2000 }
          );
        })
        .finally((_) => {
          // Set isRetrievingOpinions to false after request
          this.isLoadingMoreFollowersAndFollowings = false;
        });
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
