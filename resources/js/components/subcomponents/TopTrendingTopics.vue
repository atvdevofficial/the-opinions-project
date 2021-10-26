<template>
  <v-card rounded="lg" elevation="0">
    <v-card-title class="font-weight-bold">
      Top / Trending Topics
    </v-card-title>

    <!-- Metrics -->
    <v-card-text>
      <v-list>
        <v-list-item
          two-line
          v-for="(topTrendingTopic, index) in topTrendingTopics"
          :key="index"
        >
          <v-list-item-content>
            <v-list-item-title>{{ topTrendingTopic.text }}</v-list-item-title>
            <v-list-item-subtitle class="font-italic"
              >{{ topTrendingTopic.total }} opinions</v-list-item-subtitle
            >
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: "TopTrendingTopics",
  data() {
    return {
      isRetrievingTopTrendingTopics: false,
      topTrendingTopics: [],
    };
  },
  mounted() {
    this.retrieveTopTrendingTopics();
  },
  methods: {
    retrieveTopTrendingTopics() {
      // Set isRetrievingTopTrendingTopics to true
      this.isRetrievingTopTrendingTopics = true;

      axios
        .get("/api/topics/topTrending")
        .then((response) => {
          var data = response.data;

          // Set top trending topics
          this.topTrendingTopics = data;
        })
        .catch((error) => {
          console.log(error.response.data);
        })
        .finally((_) => {
          this.isRetrievingTopTrendingTopics = false;
        });
    },
  },
};
</script>
