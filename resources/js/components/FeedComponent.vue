<template>
  <div>
    <!-- Start of Toolbar -->
    <v-app-bar dense color="#FFD561" elevation="0">
      <v-toolbar-title
        class="font-weight-black"
        style="cursor: pointer"
        @click="retrieveOpinionsFeed"
        >Opinions</v-toolbar-title
      >
      <v-spacer></v-spacer>

      <!-- Chat button -->
      <v-btn icon @click="$router.push({ name: 'chats' })">
        <box-icon name="chat"></box-icon>
      </v-btn>

      <!-- Profile dialog button on ly visible to below medium screens -->
      <v-btn icon class="d-flex d-md-none" @click="profileDialog = true">
        <box-icon name="user-circle"></box-icon>
      </v-btn>

      <!-- Logout button -->
      <div class="d-none d-md-flex">
        <logout-dialog
          :showDialog="logoutDialog"
          @close="logoutDialogClose"
        ></logout-dialog>
      </div>
    </v-app-bar>
    <!-- End of Toolbar -->

    <!-- Start of Body -->
    <v-main>
      <v-container min-width="1366px">
        <v-row>
          <!-- Start of Profile Card -->
          <v-col cols="12" md="4" lg="3" xl="2" class="d-none d-md-flex">
            <!-- Profile card -->
            <v-card rounded="lg" elevation="0">
              <v-card-text class="pa-0">
                <!-- Profile Card Dialog -->
                <profile-card-dialog
                  :showDialog="profileDialog"
                  @close="profileDialogClose"
                  @shared-opinions="retrieveCritiqueOpinions"
                ></profile-card-dialog>

                <!-- Opinion Dialog -->
                <div class="px-4">
                  <opinion-dialog
                    :showDialog="opinionDialog"
                    @close="opinionDialogClose"
                    @opinion-created="opinionCreated"
                  ></opinion-dialog>
                </div>

                <div class="mt-2 px-4">
                  <v-btn
                    x-small
                    text
                    block
                    rounded
                    depressed
                    @click="retrieveCritiqueOpinions"
                    >See shared opinions</v-btn
                  >
                </div>
              </v-card-text>
            </v-card>
          </v-col>
          <!-- End of Profile Card -->

          <!-- Start of Feed -->
          <v-col cols="12" md="8" lg="9" xl="8">
            <v-container class="my-8" v-if="isRetrievingOpinions">
              <div class="text-center">
                <v-progress-circular
                  :size="50"
                  indeterminate
                  color="primary"
                ></v-progress-circular>
              </div>
              <div class="mt-4 text-center font-italic caption">
                Retrieving opinions, please wait...
              </div>
            </v-container>
            <v-container
              class="my-8"
              v-if="!isRetrievingOpinions && opinions.length == 0"
            >
              <div class="mt-4 text-center font-italic">
                Oops, looks like there are no opinions out there, share yours
                now!
              </div>
            </v-container>
            <v-row v-if="!isRetrievingOpinions && opinions.length > 0">
              <v-col
                cols="12"
                v-for="(opinion, index) in opinions"
                :key="index"
              >
                <!-- Opinion Card Goes Here -->
                <opinion-card
                  :index="index || 0"
                  :id="opinion.id || 0"
                  :name="opinion.critique.name || 'name'"
                  :username="opinion.critique.username || 'username'"
                  :text="opinion.text || 'text'"
                  :topics="opinion.topics || []"
                  :liked="opinion.liked_by_user || false"
                  :likes="opinion.like_count || 0"
                  :timestamp="opinion.created_at || 'timestamp'"
                  @change="opinionUpdated"
                ></opinion-card>
              </v-col>
              <v-col cols="12">
                <v-btn
                  block
                  small
                  text
                  depressed
                  @click="loadMoreOpinions"
                  v-if="paginationLinks.next"
                >
                  Load more opinions
                </v-btn>
              </v-col>
            </v-row>
          </v-col>
          <!-- End of Feed -->

          <!-- Start of Top / Trending Topics -->
          <v-col cols="12" xl="2" class="d-none d-xl-flex">
            <top-trending-topics></top-trending-topics>
          </v-col>
          <!-- End of Top / Trending Topics -->
        </v-row>
      </v-container>
    </v-main>
    <!-- End of Body -->

    <!-- Fab button for opinion dialog -->
    <v-footer app color="transparent" class="d-md-none">
      <v-btn
        absolute
        right
        fab
        elevation="2"
        color="primary"
        style="margin-bottom: 6rem; margin-right: 1rem;"
        @click="opinionDialog = true"
      >
        <box-icon name="plus"></box-icon>
      </v-btn>
    </v-footer>
  </div>
</template>

<script>
import ProfileCardDialog from "./subcomponents/ProfileCardDialog.vue";
import OpinionDialog from "./subcomponents/OpinionDialog.vue";
import LogoutDialog from "./subcomponents/LogoutDialog.vue";
import OpinionCard from "./subcomponents/OpinionCard.vue";
import TopTrendingTopics from "./subcomponents/TopTrendingTopics.vue";

export default {
  name: "FeedComponent",
  components: {
    ProfileCardDialog,
    OpinionDialog,
    LogoutDialog,
    OpinionCard,
    TopTrendingTopics,
  },
  data() {
    return {
      isRetrievingOpinions: false,
      profileDialog: false,
      opinionDialog: false,
      logoutDialog: false,

      opinions: [],
      paginationLinks: {
        first: null,
        last: null,
        prev: null,
        next: null,
      },
    };
  },
  mounted() {
    this.retrieveOpinionsFeed();
  },
  methods: {
    profileDialogClose(value) {
      this.profileDialog = value;
    },

    opinionDialogClose(value) {
      this.opinionDialog = value;
    },

    logoutDialogClose(value) {
      this.logoutDialog = value;
    },

    // Retrieve opinions feed
    retrieveOpinionsFeed() {
      // Set isRetrievingOpinions to true
      this.isRetrievingOpinions = true;

      axios
        .get("/api/feed")
        .then((response) => {
          let data = response.data;

          // Set opinions to data
          this.opinions = data.data;

          // Set pagination links
          this.paginationLinks = data.links;
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
          this.isRetrievingOpinions = false;
        });
    },

    // Load more opinions
    loadMoreOpinions() {
      axios
        .get(this.paginationLinks.next)
        .then((response) => {
          let data = response.data;

          // Concat opinions
          this.opinions = this.opinions.concat(data.data);

          // Set pagination links
          this.paginationLinks = data.links;
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
          this.isRetrievingOpinions = false;
        });
    },

    // Retrieve critique opinions
    retrieveCritiqueOpinions() {
      // Set isRetrievingOpinions to true
      this.isRetrievingOpinions = true;

      // Retrieve current authenticated crituque id from session storage
      var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

      axios
        .get(`/api/critiques/${critiqueId}/opinions`)
        .then((response) => {
          let data = response.data;

          // Set opinions to data
          this.opinions = data;
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
          this.isRetrievingOpinions = false;
        });
    },

    // Handles opinion dialog opinion-created event
    opinionCreated(data) {
      this.opinions.unshift(data);
    },

    opinionUpdated(e) {
        this.opinions[e.index]['liked_by_user'] = e.liked

        if (e.liked) {
            this.opinions[e.index]['like_count'] += 1;
        } else {
            this.opinions[e.index]['like_count'] -= 1;
        }
    }
  },
};
</script>
