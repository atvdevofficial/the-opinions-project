<template>
  <div>
    <!-- Start of App Bar -->
    <v-app-bar dense color="#FFD561" elevation="0">
      <v-toolbar-title class="font-weight-black">Opinions</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon>
        <box-icon name="chat"></box-icon>
      </v-btn>
      <v-btn icon class="d-flex d-md-none" @click="profileDialog = true">
        <box-icon name="user-circle"></box-icon>
      </v-btn>
    </v-app-bar>
    <!-- End of App Bar -->

    <v-list-item class="grow">
      <v-list-item-avatar color="#FFEAB1">
        <box-icon name="user" size="sm"></box-icon>
      </v-list-item-avatar>

      <v-list-item-content>
        <v-list-item-title>
          <div>Elen Mac Doe</div>
          <div class="caption font-italic">elenmacdoe</div>
        </v-list-item-title>
      </v-list-item-content>

      <!-- <v-list-item-action>
        <v-list-item-action-text>
          <v-avatar color="#FFEAB1" size="40">
            <box-icon name="user" size="sm"></box-icon>
          </v-avatar>
        </v-list-item-action-text>
      </v-list-item-action> -->
    </v-list-item>

    <div class="pa-1 caption font-italic text-center reminder">
      Messages are automatically deleted after 24 hours
    </div>

    <!-- <v-row no-gutters class="pl-4 pt-2">
      <v-col cols="12"> Elen Mac Doe </v-col>
      <v-col cols="12" class="caption"> elanmaedoe </v-col>
    </v-row> -->

    <!-- Start of Body -->
    <v-main class="pa-0">
      <!-- <v-virtual-scroll :items="items" :item-height="200" height="300">
        <template v-slot:default="{ item }">
          <v-list-item>
            <v-list-item-content class="caption">
              {{ item }} Lorem, ipsum dolor sit amet consectetur adipisicing elit. Minima, rerum provident repellendus amet deleniti totam nesciunt animi nemo incidunt corporis eos! A beatae voluptatem sunt asperiores, ipsa veritatis unde dolorum possimus. Perspiciatis, neque, sit saepe veritatis rerum voluptatum molestias minima enim eum maiores error. Blanditiis sit voluptatem cupiditate maiores ratione.
              <v-list-item-subtitle class="mt-2 caption font-italic">
                - You | 09/28/2021 14:00
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
        </template>
      </v-virtual-scroll> -->
      <v-list two-line>
        <v-list-item
          v-for="index in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]"
          :key="index"
        >
          <v-list-item-content class="caption">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quidem
            doloremque natus voluptatum adipisci nihil magnam aspernatur earum
            consequatur quas cumque!
            <v-list-item-subtitle class="mt-2 caption font-italic">
              - You | 09/28/2021 14:00
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-main>

    <v-footer app color="#ffeab1" min-height="72">
      <v-textarea dense flat hide-details solo rows="2"></v-textarea>
      <!-- <v-btn depressed color="primary" class="ml-4 pa-0">
        <box-icon name='send' type='solid' ></box-icon>
      </v-btn> -->
      <v-btn class="ml-4" text icon>
        <box-icon type="solid" name="up-arrow"></box-icon>
      </v-btn>
    </v-footer>
  </div>
</template>

<script>
export default {
  name: "ChatListComponent",
  data() {
    return {
      items: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    };
  },
};
</script>

<style lang="scss" scoped>
.wrap-text {
  -webkit-line-clamp: unset !important;
}
.reminder {
  background-color: #ffeab1;
}
</style>
