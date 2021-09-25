<template>
  <div>
    <!-- Start of Toolbar -->
    <v-app-bar dense color="#FFD561" elevation="0">
      <v-toolbar-title class="font-weight-black">Opinions</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon>
        <box-icon name="chat"></box-icon>
      </v-btn>
    </v-app-bar>
    <!-- End of Toolbar -->

    <!-- Start of Body -->
    <v-main>
      <v-container min-width="1366px">
        <v-row>
          <!-- Start of Profile Card -->
          <v-col cols="12" sm="2">
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
                    <div class="text-center">John Mac Doe</div>
                    <div class="caption text-center">The Business Analyst</div>
                  </v-col>
                </v-row>
              </v-card-title>

              <!-- Metrics -->
              <v-card-text>
                <v-row align="center" justify="center">
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
                </v-row>
                <v-divider class="mt-6"></v-divider>
              </v-card-text>

              <!-- Actions -->
              <v-card-actions>
                <v-btn
                  block
                  rounded
                  depressed
                  color="#FFD561"
                  class="font-weight-black"
                  >Share an Opinion</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-col>
          <!-- End of Profile Card -->

          <v-col cols="12" sm="8">
            <!-- <v-sheet min-height="100vh" rounded="lg"> </v-sheet> -->
            <v-row>
              <v-col cols="12" v-for="(index) in [1,2,3,4,5]" :key="index">
                <v-card elevation="0">
                  <!-- Image, Name, and Timestamps -->
                  <v-card-title>
                    <v-row align="center" justify="center">
                      <v-col cols="1" class="text-center">
                        <v-avatar color="#FFEAB1" size="50">
                          <box-icon name="user" size="sm"></box-icon>
                        </v-avatar>
                      </v-col>
                      <v-col cols="10">
                        <div>John Mac Doe</div>
                        <div class="caption font-italic">
                          The Business Analyst
                        </div>
                      </v-col>
                      <v-col cols="1">
                        <div class="caption">10 mins ago</div>
                      </v-col>
                    </v-row>
                  </v-card-title>

                  <!-- Metrics -->
                  <v-card-text>
                    <div>
                      Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                      Porro, qui itaque. Aspernatur, voluptas autem repellendus
                      eos beatae consectetur fugit consequuntur optio dolore
                      debitis fugiat eius molestiae. Optio qui facere expedita
                      a, eveniet esse illum inventore ipsa quod dolorum modi
                      voluptatem deserunt quo aut deleniti quae consequatur ab
                      totam. Eos voluptas molestiae qui iure eveniet modi cumque
                      nam id nesciunt, odio consequuntur magni. Quod, accusamus
                      sit! Id a in officiis ipsam, quidem, accusamus dolore,
                      quia delectus sed sapiente corporis itaque ad nam. Magni
                      voluptate facilis consequuntur deserunt alias corrupti
                      totam reiciendis minima necessitatibus, excepturi ex
                      labore aspernatur dignissimos atque esse quibusdam.
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
                      </v-btn
                    >
                  </v-card-actions>
                </v-card>
              </v-col>
            </v-row>
          </v-col>

          <v-col cols="12" sm="2">
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
        </v-row>
      </v-container>
    </v-main>
    <!-- End of Body -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      active: null,
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
