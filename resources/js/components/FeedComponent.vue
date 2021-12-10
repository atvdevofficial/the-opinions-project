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

      <!-- Search button -->
      <v-btn icon @click="isSearching = true">
        <box-icon name="search"></box-icon>
      </v-btn>

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
      <v-container min-width="1366px" class="pa-0">
        <v-row no-gutters>
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
              </v-card-text>
            </v-card>
          </v-col>
          <!-- End of Profile Card -->

          <!-- Start of Feed -->
          <v-col cols="12" md="8" lg="9" xl="8">
            <!-- Search Component -->
            <!-- Container for search results -->
            <v-container v-if="isSearching">
              <!-- Search text field -->
              <v-text-field
                solo
                placeholder="Search something here"
                v-model="search"
                @input="debounceInput"
                :loading="isRetrievingSearchResults"
                hide-details
              >
                <template v-slot:append>
                  <v-btn text depressed @click="searchCanceled"> Cancel </v-btn>
                </template>
              </v-text-field>

              <v-container class="my-8" v-if="isRetrievingSearchResults">
                <div class="text-center">
                  <v-progress-circular
                    :size="50"
                    indeterminate
                    color="primary"
                  ></v-progress-circular>
                </div>
                <div class="mt-4 text-center font-italic caption">
                  Retrieving search results, please wait...
                </div>
              </v-container>

              <v-container
                class="pa-0 mt-2"
                v-if="
                  (!!searchResult.critiques.data ||
                    !!searchResult.topics.data ||
                    !!searchResult.opinions.data) &&
                  !isRetrievingSearchResults
                "
              >
                <!-- Search result tabs -->
                <v-tabs v-model="searchTab" grow>
                  <v-tab v-for="item in searchTabItems" :key="item">
                    {{ item }}
                  </v-tab>
                </v-tabs>

                <v-tabs-items v-model="searchTab">
                  <!-- Profile Dialog -->
                  <v-dialog v-model="otherProfileDialog" max-width="400px">
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
                            <div class="text-center">
                              {{ viewingCritiqueProfile.name }}
                            </div>
                            <div class="caption text-center">
                              {{ viewingCritiqueProfile.username }}
                            </div>
                          </v-col>
                        </v-row>
                      </v-card-title>

                      <v-card-text>
                        <v-btn
                          block
                          small
                          depressed
                          color="#FFD561"
                          class="font-weight-bold px-4 mb-4"
                          v-if="!viewingCritiqueProfile.is_following"
                          :loading="isFollowingUnfollowingCritique"
                          @click="
                            followUnfollowCritique(
                              'follow',
                              viewingCritiqueProfile.id,
                              viewingCritiqueProfile.index
                            )
                          "
                        >
                          Follow
                        </v-btn>

                        <v-btn
                          block
                          small
                          depressed
                          color="default"
                          class="font-weight-bold mb-4"
                          v-if="viewingCritiqueProfile.is_following"
                          :loading="isFollowingUnfollowingCritique"
                          @click="
                            followUnfollowCritique(
                              'unfollow',
                              viewingCritiqueProfile.id,
                              viewingCritiqueProfile.index
                            )
                          "
                        >
                          Unfollow
                        </v-btn>
                        <v-row dense align="center" justify="center">
                          <!-- Profile Metrics (Likes, Followers, Followings) -->
                          <v-col cols="4">
                            <div class="text-center">
                              {{ viewingCritiqueProfile.statistics.topics }}
                            </div>
                            <div class="caption text-center font-italic">
                              Topics
                            </div>
                          </v-col>

                          <v-col cols="4">
                            <div class="text-center">
                              {{ viewingCritiqueProfile.statistics.followers }}
                            </div>
                            <div class="caption text-center font-italic">
                              Followers
                            </div>
                          </v-col>

                          <v-col cols="4">
                            <div class="text-center">
                              {{ viewingCritiqueProfile.statistics.followings }}
                            </div>
                            <div class="caption text-center font-italic">
                              Followings
                            </div>
                          </v-col>
                        </v-row>
                      </v-card-text>
                    </v-card>
                  </v-dialog>

                  <v-tab-item v-for="item in searchTabItems" :key="item">
                    <!-- Critiques -->
                    <v-container v-if="item == 'Critiques'">
                      <div v-if="searchResult.critiques.data.length > 0">
                        <v-card
                          elevation="0"
                          v-for="(critique, index) in searchResult.critiques
                            .data"
                          :key="index"
                          @click="showCritiqueProfile(critique, index)"
                        >
                          <v-card-title class="px-2 py-0">
                            <v-list-item class="grow px-0">
                              <v-list-item-avatar color="#FFEAB1">
                                <box-icon name="user" size="sm"></box-icon>
                              </v-list-item-avatar>

                              <v-list-item-content>
                                <v-list-item-title>
                                  <div>{{ critique.name }}</div>
                                  <div class="caption font-italic">
                                    {{ critique.username }}
                                  </div>
                                </v-list-item-title>
                              </v-list-item-content>

                              <v-list-item-action>
                                <div
                                  class="caption font-italic"
                                  v-if="critique.is_following"
                                >
                                  Following
                                </div>
                              </v-list-item-action>
                            </v-list-item>
                          </v-card-title>
                        </v-card>
                        <v-col cols="12">
                          <v-btn
                            block
                            small
                            text
                            depressed
                            @click="loadMoreSearchResults('critiques')"
                            v-if="searchResult.critiques.next_page_url"
                            :loading="isLoadingMore"
                          >
                            Load more critiques
                          </v-btn>
                        </v-col>
                      </div>

                      <v-container
                        v-else
                        class="subtitle text-center font-italic"
                      >
                        No Critique Profile Found
                      </v-container>
                    </v-container>

                    <!-- Topics -->
                    <v-container v-if="item == 'Topics'">
                      <div v-if="searchResult.topics.data.length > 0">
                        <v-card
                          elevation="0"
                          v-for="(topic, index) in searchResult.topics.data"
                          :key="index"
                          @click="
                            retrieveOpinionsFeedWithFilter({
                              filter: 'topic',
                              ...topic,
                            })
                          "
                        >
                          <v-card-title class="pa-0">
                            <v-list-item class="grow px-0">
                              <v-list-item-content>
                                <v-list-item-title>
                                  <div>{{ topic.text }}</div>
                                  <div class="caption font-italic">
                                    {{ topic.opinion_count }}
                                    opinions
                                  </div>
                                </v-list-item-title>
                              </v-list-item-content>
                              <v-list-item-action>
                                <v-btn
                                  small
                                  depressed
                                  color="#FFD561"
                                  class="font-weight-bold px-4"
                                  v-if="!topic.is_following"
                                  :loading="isFollowingUnfollowingTopic"
                                  @click.stop.prevent="
                                    followUnfollowTopic(
                                      'follow',
                                      topic.id,
                                      index
                                    )
                                  "
                                >
                                  Follow
                                </v-btn>

                                <v-btn
                                  small
                                  depressed
                                  color="default"
                                  class="font-weight-bold"
                                  v-if="topic.is_following"
                                  :loading="isFollowingUnfollowingTopic"
                                  @click.stop.prevent="
                                    followUnfollowTopic(
                                      'unfollow',
                                      topic.id,
                                      index
                                    )
                                  "
                                >
                                  Unfollow
                                </v-btn>
                              </v-list-item-action>
                            </v-list-item>
                          </v-card-title>
                        </v-card>
                        <v-col cols="12">
                          <v-btn
                            block
                            small
                            text
                            depressed
                            @click="loadMoreSearchResults('topics')"
                            v-if="searchResult.topics.next_page_url"
                            :loading="isLoadingMore"
                          >
                            Load more topics
                          </v-btn>
                        </v-col>
                      </div>

                      <v-container
                        v-else
                        class="subtitle text-center font-italic"
                      >
                        No Topics Found
                      </v-container>
                    </v-container>

                    <!-- Opinions -->
                    <v-container v-if="item == 'Opinions'">
                      <v-row v-if="searchResult.opinions.data.length > 0">
                        <v-col
                          cols="12"
                          v-for="(opinion, index) in searchResult.opinions.data"
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
                            @change="resultOpinionUpdated"
                            @topic-clicked="retrieveOpinionsFeedWithFilter"
                            @username-clicked="retrieveOpinionsFeedWithFilter"
                          ></opinion-card>
                        </v-col>
                        <v-col cols="12">
                          <v-btn
                            block
                            small
                            text
                            depressed
                            @click="loadMoreSearchResults('opinions')"
                            v-if="searchResult.opinions.next_page_url"
                            :loading="isLoadingMore"
                          >
                            Load more opinions
                          </v-btn>
                        </v-col>
                      </v-row>

                      <v-container
                        v-else
                        class="subtitle text-center font-italic"
                      >
                        No Opinions Found
                      </v-container>
                    </v-container>
                  </v-tab-item>
                </v-tabs-items>
              </v-container>
            </v-container>

            <!-- Main Feed -->
            <!-- v-if="isSearching" -->
            <v-container v-else>
              <!-- Retrieving view -->
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

              <!-- Opinons is empty -->
              <v-container
                class="my-8"
                v-if="!isRetrievingOpinions && opinions.data.length == 0"
              >
                <div class="mt-4 text-center font-italic">
                  Oops, looks like there are no opinions out there, share yours
                  now!
                </div>
              </v-container>

              <!-- Load opinions -->
              <v-row v-if="!isRetrievingOpinions && opinions.data.length > 0">
                <v-col cols="12" v-if="filter.text && filter.type">
                  <v-container color="primary">
                    <div class="font-weight-black text-center font-italic">
                      <span v-if="filter.type == 'topic'">#</span>
                      <span v-else>@</span>
                      {{ filter.text }}
                    </div>
                  </v-container>
                </v-col>
                <v-col
                  cols="12"
                  v-for="(opinion, index) in opinions.data"
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
                    @change="feedOpinionUpdated"
                    @topic-clicked="retrieveOpinionsFeedWithFilter"
                    @username-clicked="retrieveOpinionsFeedWithFilter"
                  ></opinion-card>
                </v-col>
                <v-col cols="12">
                  <v-btn
                    block
                    small
                    text
                    depressed
                    @click="loadMoreOpinions"
                    v-if="opinions.links.next"
                    :loading="isLoadingMore"
                  >
                    Load more opinions
                  </v-btn>
                </v-col>
              </v-row>
            </v-container>
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
        style="margin-bottom: 6rem; margin-right: 1rem"
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
      isSearching: false,
      isLoadingMore: false,
      isFollowingUnfollowingTopic: false,
      isFollowingUnfollowingCritique: false,
      isRetrievingOpinions: false,
      isRetrievingSearchResults: false,

      otherProfileDialog: false,
      profileDialog: false,
      opinionDialog: false,
      logoutDialog: false,

      opinions: {
        data: [],
        links: {
          first: null,
          last: null,
          prev: null,
          next: null,
        },
      },

      filter: {
        text: null,
        type: null,
      },
      search: null,
      searchTab: "Critiques",
      searchTabItems: ["Critiques", "Topics", "Opinions"],
      searchResult: {
        critiques: {
          data: null,
          next_page_url: null,
        },
        topics: {
          data: null,
          next_page_url: null,
        },
        opinions: {
          data: null,
          next_page_url: null,
        },
      },

      viewingCritiqueProfile: {
        id: null,
        name: null,
        username: null,
        statistics: {
          topics: 0,
          followers: 0,
          followings: 0,
        },
        is_following: false,
      },
    };
  },
  mounted() {
    this.retrieveOpinionsFeed();

    let userId = sessionStorage.getItem("userId");
    Echo.private('test.' + userId)
        .listen('TestEvent', (e) => {
            console.log(e);
        })
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

    // Retrieve opinions feed with filter
    retrieveOpinionsFeedWithFilter(e) {
      // Format url for request
      let url = "/api/feed";
      if (e.filter == "topic") {
        url += `?topic=${e.text}`;
        this.filter.type = e.filter;
        this.filter.text = e.text;
      } else if (e.filter == "critique") {
        url += `?critique=${e.username}`;
        this.filter.type = e.filter;
        this.filter.text = e.username;
      }

      // Set isRetrievingOpinions to true
      this.isRetrievingOpinions = true;
      // Set isSearching to false
      this.isSearching = false;

      axios
        .get(url)
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

    // Retrieve opinions feed
    retrieveOpinionsFeed() {
      // Set isRetrievingOpinions to true
      this.isRetrievingOpinions = true;
      this.filter = { text: null, type: null };

      axios
        .get("/api/feed")
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

    // Load more opinions
    loadMoreOpinions() {
      // Set isLoadingMore to true
      this.isLoadingMore = true;

      axios
        .get(this.opinions.links.next)
        .then((response) => {
          let data = response.data;

          // Concat opinions
          this.opinions.data = this.opinions.data.concat(data.data);
          this.opinions.links = data.links;
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
          this.isLoadingMore = false;
        });
    },

    // Load more opinions
    loadMoreSearchResults(result) {
      // Set isLoadingMore to true
      this.isLoadingMore = true;

      axios
        .get(this.searchResult[result].next_page_url)
        .then((response) => {
          console.log(response.data);
          let data = response.data;

          // Concat opinions
          this.searchResult[result].data = this.searchResult[
            result
          ].data.concat(data[result].data);
          this.searchResult[result].next_page_url = data[result].next_page_url;
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
          this.isLoadingMore = false;
        });
    },

    // Retrieve critique opinions
    retrieveCritiqueOpinions() {
      // Set isRetrievingOpinions to true
      this.isRetrievingOpinions = true;

      // Retrieve current authenticated critque id from session storage
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
      this.opinions.data.unshift(data);
    },

    feedOpinionUpdated(e) {
      this.opinionUpdated(e, this.opinions.data);
    },

    resultOpinionUpdated(e) {
      this.opinionUpdated(e, this.searchResult.opinions.data);
    },

    opinionUpdated(e, opinionArray) {
      opinionArray[e.index]["liked_by_user"] = e.liked;

      if (e.liked) {
        opinionArray[e.index]["like_count"] += 1;
      } else {
        opinionArray[e.index]["like_count"] -= 1;
      }
    },

    retrieveSearchResult() {
      if (!!this.search) {
        this.isRetrievingSearchResults = true;

        axios
          .get(`/api/search?search=${this.search}`)
          .then((response) => {
            let data = response.data;

            this.searchResult = data;
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
            this.isRetrievingSearchResults = false;
          });
      } else {
        this.searchResult = {
          critiques: {
            data: null,
            next_page_url: null,
          },
          topics: {
            data: null,
            next_page_url: null,
          },
          opinions: {
            data: null,
            next_page_url: null,
          },
        };
      }
    },

    followUnfollowTopic(type, topicId, index) {
      // Set isFollowingUnfollowingTopic to true
      this.isFollowingUnfollowingTopic = true;

      // Retrieve current authenticated critque id from session storage
      var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

      axios
        .put(`/api/critiques/${critiqueId}/follows/topics/${topicId}/${type}`)
        .then((response) => {
          this.searchResult.topics.data[index].is_following =
            type == "follow" ? true : false;
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
          this.isFollowingUnfollowingTopic = false;
        });
    },

    followUnfollowCritique(type, critiqueId, index) {
      // Set isFollowingUnfollowingCritique to true
      this.isFollowingUnfollowingCritique = true;

      axios
        .put(`/api/critiques/follows/critiques/${critiqueId}/${type}`)
        .then((response) => {
          this.searchResult.critiques.data[index].is_following =
            type == "follow" ? true : false;
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
          this.isFollowingUnfollowingCritique = false;
        });
    },

    showCritiqueProfile(critique, index) {
      this.otherProfileDialog = true;
      this.viewingCritiqueProfile = critique;
      this.viewingCritiqueProfile.index = index;
    },

    searchCanceled() {
      this.isSearching = false;
      this.search = null;
      this.retrieveSearchResult()
    },

    debounceInput: _.debounce(function () {
      this.retrieveSearchResult();
    }, 500),
  },
};
</script>
