<template>
  <div>
    <!-- Start of Toolbar -->
    <v-app-bar dense color="#FFD561" elevation="0">
      <v-app-bar-nav-icon @click="$router.push({ name: 'feed' })">
        <box-icon name="arrow-back"></box-icon>
      </v-app-bar-nav-icon>
      <v-toolbar-title class="font-weight-black">Chat</v-toolbar-title>
    </v-app-bar>
    <!-- End of Toolbar -->

    <!-- Start of Body -->
    <v-main>
      <v-container min-width="1366px">
        <div class="caption font-italic text-center">
          Messages are automatically deleted after 24 hours
        </div>

        <v-container class="my-8" v-if="isRetrievingMessages">
          <div class="text-center">
            <v-progress-circular
              :size="50"
              indeterminate
              color="primary"
            ></v-progress-circular>
          </div>
          <div class="mt-4 text-center font-italic caption">
            Retrieving messages, please wait...
          </div>
        </v-container>

        <v-container
          class="my-8"
          v-if="!isRetrievingMessages && messages.length == 0"
        >
          <div class="mt-4 text-center font-italic">
            Oops, looks like there are no messages from and/or for you here.
          </div>
        </v-container>

        <v-list two-line v-if="!isRetrievingMessages && messages.length > 0">
          <v-list-item
            v-for="(message, index) in messages"
            :key="index"
            @click="$router.push({ name: 'chat', params: { id: message.critique_id } })"
          >
            <v-list-item-avatar color="#FFEAB1">
              <box-icon name="user" size="sm"></box-icon>
            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title>
                <div>{{ message.critique_name }}</div>
                <div class="caption font-italic">
                  {{ message.text }}
                </div>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-container>
    </v-main>
  </div>
</template>

<script>
export default {
  name: "ChatsComponent",
  data() {
    return {
      isRetrievingMessages: false,

      messages: [],
    };
  },
  mounted() {
    this.retrieveMessages();
  },
  methods: {
    retrieveMessages() {
      // Set isRetievingMessages to true
      this.isRetrievingMessages = true;

      // Retrieve current authenticated crituque id from session storage
      var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

      axios
        .get(`/api/critiques/${critiqueId}/messages`)
        .then((response) => {
          let data = response.data;

          // Set messages to data
          this.messages = data;
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
          this.isRetrievingMessages = false;
        });
    },
  },
};
</script>
