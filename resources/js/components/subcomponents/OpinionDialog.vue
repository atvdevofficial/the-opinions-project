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
          <!-- Opinion textfield -->
          <v-textarea
            counter
            auto-grow
            autofocus
            color="#FFD561"
            :rules="opinionTextFieldRules"
            placeholder="Your opinion goes here"
          ></v-textarea>

          <!-- Topics select field -->
          <v-autocomplete
            multiple
            item-text="text"
            item-value="id"
            :items="topics"
            placeholder="Select your topic of choice"
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
        </v-container>
      </v-card-text>

      <!-- Card actions -->
      <v-card-actions>
        <v-spacer></v-spacer>
        <!-- Cancel button -->
        <v-btn color="default" text @click="opinionDialog = false">
          Cancel
        </v-btn>

        <!-- Share button -->
        <v-btn
          rounded
          depressed
          color="#FFD561"
          class="font-weight-black px-8"
          @click="opinionDialog = false"
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
  data() {
    return {
      opinionDialog: false,

      topics: [
        { id: 1, text: "Not Financial Advise" },
        { id: 2, text: "Financial Advise" },
        { id: 3, text: "Cryto Currency" },
        { id: 4, text: "Stock Market" },
      ],

      opinionTextFieldRules: [
        (v) => !!v || "Please share your opinion",
        (v) =>
          (v && v.length <= 255) ||
          "Share your opinion in less than 255 characters",
      ],
    };
  },
};
</script>r
