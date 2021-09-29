<template>
  <div>
    <!-- Start of App Bar -->
    <v-app-bar app dense color="#FFD561" elevation="0">
      <v-app-bar-nav-icon to="/chat-list">
        <box-icon name="arrow-back"></box-icon>
      </v-app-bar-nav-icon>
      <v-toolbar-title class="pa-0">
        <v-list-item class="grow pa-0">
          <v-list-item-avatar color="#FFEAB1">
            <box-icon name="user" size="sm"></box-icon>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>
              <div>Elen Mac Doe</div>
              <div class="caption font-italic">elenmacdoe</div>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-toolbar-title>
    </v-app-bar>
    <!-- End of App Bar -->

    <!-- Start of Body -->
    <v-main class="pa-0">
      <v-list two-line class="pa-0">
        <v-list-item
          v-for="(message, index) in messages"
          :key="index"
          :style="
            index % 2 == 0
              ? 'background-color: #EEEEEE;'
              : 'background-color: #FFFFFF;'
          "
        >
          <v-list-item-content class="caption">
            {{ message }}
            <v-list-item-subtitle class="mt-2 caption font-italic">
              - 09/28/2021 14:00
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-main>

    <v-footer app color="#ffeab1" min-height="100">
      <v-textarea
        dense
        flat
        hide-details
        solo
        rows="2"
        v-model="message"
      ></v-textarea>
      <v-btn text class="ml-4 font-weight-bold" @click="sendMessage">
        Send
      </v-btn>
    </v-footer>
  </div>
</template>

<script>
export default {
  name: "ChatListComponent",
  data() {
    return {
      message: null,
      messages: [
        "The point of this vue directive is to keep things scrolled to the bottom",
        "Let's load a couple of random bacon related messages.",
        "Okay, now let's see how this div is always scrolled to the bottom",
      ],
    };
  },
  methods: {
    async sendMessage() {
      await this.messages.push(this.message);
      this.message = null;
      window.scrollTo(0, document.body.scrollHeight);
    },
  },
};
</script>

<style lang="scss" scoped>
html {
  overflow-y: auto;
}
.wrap-text {
  -webkit-line-clamp: unset !important;
}
.reminder {
  background-color: #ffeab1;
}
</style>
