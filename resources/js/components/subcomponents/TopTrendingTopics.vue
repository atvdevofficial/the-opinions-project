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
            <v-list-item-subtitle
              >{{ topTrendingTopic.value }} Opinions</v-list-item-subtitle
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
      topTrendingTopics: [
        { text: "Election2040", value: "2.1M" },
        { text: "Pandemic", value: "1.1M" },
        { text: "Climate Change", value: "500K" },
        { text: "CryptoCurrency", value: "250K" },
      ],
    };
  },
};
</script>
