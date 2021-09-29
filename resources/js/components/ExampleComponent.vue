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
            <v-card rounded="lg" elevation="0">
              <!-- Image and Name -->
              <v-card-title>
                <v-row align="center" justify="center">
                  <v-col cols="4">
                    <v-avatar color="#FFEAB1" size="75">
                      <box-icon name="user" size="md"></box-icon>
                    </v-avatar>
                  </v-col>
                  <v-col cols="8">
                    <div class="text-center">Elen Mac Doe</div>
                    <div class="caption text-center">elenmacdoe</div>
                  </v-col>
                </v-row>
              </v-card-title>

              <!-- Metrics -->
              <v-card-text>
                <v-row dense align="center" justify="center">
                  <v-col cols="4">
                    <div class="text-center">900K</div>
                    <div class="caption text-center font-italic">Likes</div>
                  </v-col>
                  <v-col cols="4">
                    <div class="text-center">900K</div>
                    <div class="caption text-center font-italic">Followers</div>
                  </v-col>
                  <v-col cols="4">
                    <div class="text-center">900K</div>
                    <div class="caption text-center font-italic">Following</div>
                  </v-col>
                  <v-col cols="12" class="mt-2">
                    <v-dialog
                      persistent
                      v-model="profileDialog"
                      max-width="400px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          x-small
                          text
                          block
                          rounded
                          depressed
                          color="default"
                          v-bind="attrs"
                          v-on="on"
                        >
                          Update Profile
                        </v-btn>
                      </template>
                      <v-card>
                        <v-card-title></v-card-title>
                        <v-card-text>
                          <v-row dense align="center" justify="center">
                            <v-col cols="12" class="text-center">
                              <v-avatar color="#FFEAB1" size="100">
                                <box-icon name="user" size="md"></box-icon>
                              </v-avatar>
                            </v-col>
                            <v-col cols="4" class="mt-2 d-md-none">
                              <div class="text-center">900K</div>
                              <div class="caption text-center font-italic">
                                Likes
                              </div>
                            </v-col>
                            <v-col cols="4" class="mt-2 d-md-none">
                              <div class="text-center">900K</div>
                              <div class="caption text-center font-italic">
                                Followers
                              </div>
                            </v-col>
                            <v-col cols="4" class="mt-2 d-md-none">
                              <div class="text-center">900K</div>
                              <div class="caption text-center font-italic">
                                Following
                              </div>
                            </v-col>
                            <v-col cols="12" class="mt-4 d-md-none">
                              <v-divider></v-divider>
                            </v-col>
                            <v-col cols="12">
                              <v-text-field placeholder="Name"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                              <v-text-field
                                placeholder="Username"
                              ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                              <v-text-field
                                placeholder="Password"
                                type="password"
                              ></v-text-field>
                            </v-col>
                          </v-row>
                        </v-card-text>

                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn
                            text
                            color="default"
                            @click="profileDialog = false"
                          >
                            Cancel
                          </v-btn>
                          <v-btn
                            rounded
                            depressed
                            color="#FFD561"
                            class="font-weight-black px-8"
                            @click="profileDialog = false"
                          >
                            Update
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                  </v-col>
                </v-row>
                <v-divider class="mt-2"></v-divider>
              </v-card-text>

              <!-- Actions -->
              <v-card-actions>
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
                    <v-card-title>
                      <span class="subtitle-1">Share your opinion now</span>
                    </v-card-title>

                    <v-card-text>
                      <v-container>
                        <v-textarea
                          counter
                          auto-grow
                          autofocus
                          color="#FFD561"
                          :rules="formRules"
                          placeholder="Your opinion goes here"
                        ></v-textarea>
                        <v-autocomplete
                          multiple
                          item-text="text"
                          item-value="id"
                          :items="items"
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
                            <v-chip small color="#FFD561" class="caption">{{ item.text }}</v-chip>
                          </template>
                        </v-autocomplete>
                      </v-container>
                    </v-card-text>

                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn
                        color="default"
                        text
                        @click="opinionDialog = false"
                      >
                        Cancel
                      </v-btn>
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
              </v-card-actions>
            </v-card>
          </v-col>
          <!-- End of Profile Card -->

          <!-- Start of Feed -->
          <v-col cols="12" md="8" lg="9" xl="8">
            <v-row>
              <v-col cols="12" v-for="index in [1, 2, 3, 4, 5]" :key="index">
                <v-card elevation="0">
                  <!-- Image, Name, and Timestamps -->
                  <v-card-title>
                    <v-list-item class="grow pl-0">
                      <v-list-item-avatar color="#FFEAB1">
                        <box-icon name="user" size="sm"></box-icon>
                      </v-list-item-avatar>

                      <v-list-item-content>
                        <v-list-item-title>
                          <div>Elen Mac Doe</div>
                          <div class="caption font-italic">elenmacdoe</div>
                        </v-list-item-title>
                      </v-list-item-content>

                      <v-list-item-action>
                        <v-list-item-action-text>10 m</v-list-item-action-text>
                      </v-list-item-action>
                    </v-list-item>
                  </v-card-title>

                  <!-- Metrics -->
                  <v-card-text>
                    <div>
                      Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                      Neque aut nesciunt magni explicabo fugit saepe recusandae
                      consectetur quas molestias ipsum fugiat modi aliquid nam
                      laboriosam enim veritatis earum, officia sint
                      exercitationem quod eius debitis esse? Vel, dolores quia!
                      Ipsum nam saepe consequatur nemo recusandae quaerat rem
                      voluptatibus fuga. Aut, deserunt.
                    </div>
                  </v-card-text>

                  <!-- Actions -->
                  <v-card-actions>
                    <div>
                      <v-chip small color="#FFD561" class="mr-2">
                        Not Financial Advise
                      </v-chip>
                    </div>
                    <v-spacer></v-spacer>
                    <v-chip small color="#FFD561" class="mr-2 font-weight-bold">
                      90
                    </v-chip>
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
              </v-col>
            </v-row>
          </v-col>
          <!-- End of Feed -->

          <!-- Start of Top / Trending Topics -->
          <v-col cols="12" xl="2" class="d-none d-lg-flex">
            <v-card rounded="lg" elevation="0">
              <v-card-title class="font-weight-bold">
                Top / Trending Topics
              </v-card-title>

              <!-- Metrics -->
              <v-card-text>
                <v-list>
                  <v-list-item two-line>
                    <v-list-item-content>
                      <v-list-item-title>Election2040</v-list-item-title>
                      <v-list-item-subtitle>1.1M Opinions</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>

                  <v-list-item two-line>
                    <v-list-item-content>
                      <v-list-item-title>Pandemic</v-list-item-title>
                      <v-list-item-subtitle>900K Opinions</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>

                  <v-list-item two-line>
                    <v-list-item-content>
                      <v-list-item-title>Climate Change</v-list-item-title>
                      <v-list-item-subtitle>500K Opinions</v-list-item-subtitle>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
          </v-col>
          <!-- End of Top / Trending Topics -->
        </v-row>
      </v-container>
    </v-main>
    <!-- End of Body -->

    <v-footer app color="transparent" min-height="100" class="d-md-none">
      <v-btn absolute right fab elevation="2" color="primary" @click="opinionDialog = true">
        <box-icon name="plus"></box-icon>
      </v-btn>
    </v-footer>
  </div>
</template>

<script>
export default {
  name: "ExampleComponent",
  data() {
    return {
      profileDialog: null,
      opinionDialog: null,
      items: [
        { id: 1, text: "Not Financial Advise" },
        { id: 2, text: "Financial Advise" },
        { id: 3, text: "Cryto Currency" },
        { id: 4, text: "Stock Market" },
      ],
      selectedTopics: null,
      formRules: [
        (v) => !!v || "Please share your opinion",
        (v) =>
          (v && v.length <= 255) ||
          "Share your opinion in less than 255 characters",
      ],
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    addSelectedItem(e) {
      console.log(e);
    },
  },
};
</script>

<style lang="scss">
.v-application .custom-select-bg {
  color: #ffd561 !important;
  caret-color: #ffd561 !important;
}
</style>
