<template>
  <v-dialog persistent v-model="opinionDialog" max-width="600px">
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        block
        rounded
        depressed
        color="#FFD561"
        class="font-weight-black"
        v-bind="attrs"
        v-on="on"
      >
        Share an Opinion
      </v-btn>
    </template>
    <v-card>
      <!-- Card title -->
      <v-card-title>
        <span class="subtitle-1">Share your opinion now</span>
      </v-card-title>

      <!-- Card body -->
      <v-card-text>
        <v-container>
          <v-form ref="opinionForm">
            <!-- Opinion textfield -->
            <v-textarea
              counter
              auto-grow
              autofocus
              color="#FFD561"
              :rules="opinionTextFieldRules"
              placeholder="Your opinion goes here"
              v-model="opinion.text"
              :disabled="isSubmittingOpinion"
              :error-messages="opinionFormServerValidations.text"
            ></v-textarea>

            <!-- Topics select field -->
            <v-autocomplete
              multiple
              item-text="text"
              item-value="id"
              :items="topics"
              placeholder="Select your topic of choice"
              :loading="isRetrievingTopics"
              :disabled="isRetrievingTopics || isSubmittingOpinion"
              :rules="[(v) => !!v.length || 'Select atleast one topic']"
              v-model="opinion.topics"
              :error-messages="opinionFormServerValidations.topics"
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
        </v-container>
      </v-card-text>

      <!-- Card actions -->
      <v-card-actions>
        <v-spacer></v-spacer>
        <!-- Cancel button -->
        <v-btn color="default" text @click="closeDialog"> Cancel </v-btn>

        <!-- Share button -->
        <v-btn
          rounded
          depressed
          color="#FFD561"
          class="font-weight-black px-8"
          @click="submitOpinion"
          :loading="isSubmittingOpinion"
        >
          Share
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "OpinionDialog",
  props: {
    showDialog: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isSubmittingOpinion: false,
      isRetrievingTopics: false,
      opinionDialog: false,

      topics: [],

      opinion: {
        text: null,
        topics: [],
        isPublic: true,
      },

      // Server validation for opinion form
      opinionFormServerValidations: {
        text: null,
        topics: null,
        isPublic: true,
      },

      opinionTextFieldRules: [
        (v) => !!v || "Please share your opinion",
        (v) =>
          (v && v.length <= 255) ||
          "Share your opinion in less than 255 characters",
      ],
    };
  },
  mounted() {
    this.retrieveTopics();
  },
  watch: {
    showDialog: function () {
      this.opinionDialog = this.showDialog;
    },
  },
  methods: {
    // Handles opinion dialog close
    closeDialog() {
      this.opinionDialog = false;

      // Reset opinion form values
      this.opinion = {
        text: null,
        topics: [],
        isPublic: true,
      };

      // Emit a close event together with opinionDialog value
      this.$emit("close", this.opinionDialog);
    },

    // Retrieve topics
    retrieveTopics() {
      this.isRetrievingTopics = true;

      axios
        .get("/api/topics")
        .then((response) => {
          this.topics = response.data;
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
          // Set isRetrievingTopics to false
          // at the end of the request
          this.isRetrievingTopics = false;
        });
    },

    // Submit opinion
    submitOpinion() {
      // Validate form
      if (this.$refs.opinionForm.validate()) {
        // Retrieve current authenticated crituque id from session storage
        var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

        // Set isSubmittingOpinion to true
        this.isSubmittingOpinion = true;

        axios
          .post(`/api/critiques/${critiqueId}/opinions`, {
            ...this.opinion,
            is_public: this.opinion.isPublic,
          })
          .then((response) => {
            // Pop Notification
            toastr.success(
              "Your opinion will go a long way.",
              "Opinion Shared",
              { timeOut: 2000 }
            );

            // Close opinion dialog
            this.closeDialog();
          })
          .catch((error) => {
            // Server validations
            if (error.response.status == 422) {
              this.opinionFormServerValidations = {
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
            // Set isSubmittingOpinion to false
            // at the end of the request
            this.isSubmittingOpinion = false;
          });
      }
    },
  },
};
</script>r
