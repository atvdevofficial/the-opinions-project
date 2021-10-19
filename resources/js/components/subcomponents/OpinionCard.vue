<template>
  <v-card elevation="0">
    <!-- Card Title -->
    <v-card-title>
      <v-list-item class="grow pl-0">
        <v-list-item-avatar color="#FFEAB1">
          <box-icon name="user" size="sm"></box-icon>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>
            <div>{{ name }}</div>
            <div class="caption font-italic">{{ username }}</div>
          </v-list-item-title>
        </v-list-item-content>

        <v-list-item-action>
          <v-list-item-action-text>{{ timestamp }}</v-list-item-action-text>
        </v-list-item-action>
      </v-list-item>
    </v-card-title>

    <!-- Card Text -->
    <v-card-text>
      <div>
        {{ text }}
      </div>
    </v-card-text>

    <!-- Card Actions -->
    <v-card-actions>
      <!-- Topics -->
      <div>
        <v-chip
          small
          color="#FFD561"
          class="mr-2"
          v-for="(topic, index) in topics"
          :key="index"
        >
          {{ topic }}
        </v-chip>
      </div>
      <v-spacer></v-spacer>
      <!-- Like button -->
      <v-chip small color="#FFD561" class="mr-2 font-weight-bold"> 90 </v-chip>
      <v-btn
        small
        rounded
        depressed
        color="#EEEEEE"
        class="px-8 font-weight-bold"
      >
        Like
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: "OpinionCard",
  props: {
    name: {
      type: String,
      required: true,
    },
    username: {
      type: String,
      required: true,
    },
    text: {
      type: String,
      required: true,
    },
    topics: {
      type: Array,
      required: true,
    },
    likes: {
      type: Number,
      required: true,
    },
    timestamp: {
      type: String,
      required: true,
    },
  },
};
</script>
