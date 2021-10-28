<template>
  <v-card elevation="0">
    <!-- Card Title -->
    <v-card-title>
      <v-list-item class="grow px-0">
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
      <v-row>
        <v-col cols="12">
          <v-chip
            small
            class="mr-2"
            v-for="(topic, index) in topics"
            :key="index"
          >
            #{{ topic.text }}
          </v-chip>
        </v-col>
        <v-col cols="12" class="d-flex justify-end align-baseline">
          <v-chip small color="#FFD561" class="mr-2 font-weight-bold" v-if="$props.likes > 0">
            {{ likes }}
          </v-chip>
          <v-btn
            small
            rounded
            depressed
            color="#EEEEEE"
            class="px-8 font-weight-bold"
            v-if="!liked"
            @click="likeOpinion"
            :loading="isProcessing"
          >
            Like
          </v-btn>
          <!-- Like button -->
          <v-btn
            small
            rounded
            depressed
            color="primary"
            :class="liked ? 'px-6 font-weight-bold black--text' : 'px-4 font-weight-bold black--text'"
            v-if="liked"
            @click="unlikeOpinion"
            :loading="isProcessing"
          >
            Unlike
          </v-btn>
        </v-col>
      </v-row>
      <!-- <v-spacer></v-spacer> -->
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: "OpinionCard",
  props: {
    index: {
      type: Number,
      required: true,
    },
    id: {
      type: Number,
      required: true,
    },
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
    liked: {
      type: Boolean,
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
  data() {
    return {
      isProcessing: false,
    };
  },
  methods: {
    likeOpinion() {
      // Set isProcessing to true
      this.isProcessing = true;

      // Retrieve opinion id
      var opinionId = this.$props.id ?? null;

      axios
        .post(`/api/opinions/${opinionId}/like`)
        .then((response) => {
          this.$emit("change", {
            index: this.$props.index,
            liked: true,
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
          this.isProcessing = false;
        });
    },

    unlikeOpinion() {
      // Set isProcessing to true
      this.isProcessing = true;

      // Retrieve opinion id
      var opinionId = this.$props.id ?? null;

      axios
        .post(`/api/opinions/${opinionId}/unlike`)
        .then((response) => {
          this.$emit("change", {
            index: this.$props.index,
            liked: false,
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
          this.isProcessing = false;
        });
    },
  },
};
</script>
