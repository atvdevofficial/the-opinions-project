<template>
  <div>
    <!-- Start of Toolbar -->
    <v-app-bar dense color="#FFD561" elevation="0">
      <v-toolbar-title class="font-weight-black">Opinions</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon to="/chat-list">
        <box-icon name="chat"></box-icon>
      </v-btn>
      <v-btn icon class="d-flex d-md-none" @click="profileDialog = true">
        <box-icon name="user-circle"></box-icon>
      </v-btn>
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
                ></profile-card-dialog>
                <!-- Opinion Dialog -->
                <div class="px-4">
                  <opinion-dialog></opinion-dialog>
                </div>
              </v-card-text>
            </v-card>
          </v-col>
          <!-- End of Profile Card -->

          <!-- Start of Feed -->
          <v-col cols="12" md="8" lg="9" xl="8">
            <v-row>
              <v-col
                cols="12"
                v-for="(opinion, index) in opinions"
                :key="index"
              >
                <!-- Opinion Card Goes Here -->
                <opinion-card
                  :name="opinion.name"
                  :username="opinion.username"
                  :text="opinion.text"
                  :topics="opinion.topics"
                  :likes="opinion.likes"
                  :timestamp="opinion.timestamp"
                ></opinion-card>
              </v-col>
            </v-row>
          </v-col>
          <!-- End of Feed -->

          <!-- Start of Top / Trending Topics -->
          <v-col cols="12" xl="2" class="d-none d-lg-flex">
            <top-trending-topics></top-trending-topics>
          </v-col>
          <!-- End of Top / Trending Topics -->
        </v-row>
      </v-container>
    </v-main>
    <!-- End of Body -->

    <v-footer app color="transparent" min-height="100" class="d-md-none">
      <v-btn
        absolute
        right
        fab
        elevation="2"
        color="primary"
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
import OpinionCard from "./subcomponents/OpinionCard.vue";
import TopTrendingTopics from "./subcomponents/TopTrendingTopics.vue";

export default {
  name: "FeedComponent",
  components: {
    ProfileCardDialog,
    OpinionDialog,
    OpinionCard,
    TopTrendingTopics,
  },
  data() {
    return {
      profileDialog: false,
      opinionDialog: false,

      opinions: [
        {
          name: "Profile Name",
          username: "Profile Username",
          text: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam consequatur excepturi suscipit odit inventore adipisci assumenda beatae cumque? Omnis vel molestiae consectetur adipisci provident, delectus dolorum reprehenderit voluptate dolores? Exercitationem.",
          topics: ["NotFinancialAdvice"],
          likes: 100,
          timestamp: "10m",
        },
        {
          name: "Profile Name",
          username: "Profile Username",
          text: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam consequatur excepturi suscipit odit inventore adipisci assumenda beatae cumque? Omnis vel molestiae consectetur adipisci provident, delectus dolorum reprehenderit voluptate dolores? Exercitationem.",
          topics: ["FinancialAdvice"],
          likes: 2000,
          timestamp: "30m",
        },
      ],
    };
  },
  methods: {
    addSelectedItem(e) {
      console.log(e);
    },

    profileDialogClose(value) {
      this.profileDialog = value;
    },
  },
};
</script>
