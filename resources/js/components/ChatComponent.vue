<template>
  <div>
    <!-- Start of App Bar -->
    <v-app-bar app dense color="#FFD561" elevation="0">
      <v-app-bar-nav-icon @click="$router.push({ name: 'chats' })">
        <box-icon name="arrow-back"></box-icon>
      </v-app-bar-nav-icon>
      <v-toolbar-title class="pa-0">
        <v-list-item class="grow pa-0">
          <v-list-item-avatar color="#FFEAB1">
            <box-icon name="user" size="sm"></box-icon>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title>
              <div>{{ critique.name }}</div>
              <div class="caption font-italic">{{ critique.username }}</div>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-toolbar-title>
    </v-app-bar>
    <!-- End of App Bar -->

    <!-- Start of Body -->
    <div id="chatlist">
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
          Its empty here. Start a conversation now!
        </div>
      </v-container>

      <v-list
        two-line
        class="pa-0"
        v-if="!isRetrievingMessages && messages.length > 0"
      >
        <v-list-item
          v-for="(message, index) in messages"
          :key="index"
          :style="
            message.sender_id != $route.params.id
              ? 'background-color: #EEEEEE;'
              : 'background-color: #FFFFFF;'
          "
        >
          <v-list-item-content class="caption">
            {{ message.text }}
            <v-list-item-subtitle class="mt-2 caption font-italic">
              - {{ message.created_at }}
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </div>

    <v-footer app color="#ffeab1" min-height="100">
      <v-row>
        <v-col cols="9" sm="10" md="11">
          <v-form ref="formMessage">
            <v-textarea
              dense
              flat
              solo
              rows="2"
              hide-details=""
              v-model="message"
              placeholder="Your message goes here"
              :rules="[(v) => !!v || 'Required']"
              @keypress="isTypingEvent"
            ></v-textarea>
          </v-form>
        </v-col>
        <v-col cols="3" sm="2" md="1" class="d-flex align-center">
          <v-btn
            block
            text
            class="font-weight-bold"
            @click="sendMessage"
            :loading="isSendingMessages"
          >
            Send
          </v-btn>
        </v-col>
        <v-col cols="12" class="font-italic caption text-center">
          <div v-if="isTyping">The other side is typing a reply...</div>
          <div v-else>Send the critique a message now</div>
        </v-col>
      </v-row>
    </v-footer>
  </div>
</template>

<script>
export default {
  name: "ChatComponent",
  data() {
    return {
      isRetrievingMessages: false,
      isSendingMessages: false,

      chatId: null,
      message: null,
      messages: [],
      critique: {
        name: null,
        username: null,
      },

      isTyping: false,
    };
  },
  mounted() {
    // Scroll to bottom
    // Create an interval that scrolls to the bottom every 250 ms
    var intervalPaused = false;
    window.scrollToBottomInterval = setInterval(function () {
      if (!intervalPaused) {
        window.scrollTo(0, document.body.scrollHeight);
      }
    }, 250);

    // Add an onscroll event to window that
    // handels wether to pause or run interval above
    window.onscroll = function (ev) {
      if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
        intervalPaused = false;
      } else {
        intervalPaused = true;
      }
    };

    this.retrieveMessages();
  },
  destroyed() {
    // Clear interval
    clearInterval(window.scrollToBottomInterval);
  },
  methods: {
    async pushMessageToMessages(message) {
      await this.messages.push(message);
      this.message = null;
    },

    isTypingEvent() {
      console.log("Typing...");

      let channel = Echo.private("chat." + this.chatId);

      setTimeout(function () {
        channel.whisper("typing", {
          typing: true,
        });
      }, 300);
    },

    retrieveMessages() {
      // Set isRetievingMessages to true
      this.isRetrievingMessages = true;

      // Retrieve current authenticated crituque id from session storage
      var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

      // Retieve receiver
      var receiverId = this.$route.params.id;

      axios
        .get(`/api/critiques/${critiqueId}/messages?receiver=${receiverId}`)
        .then((response) => {
          let data = response.data;

          // Set messages to data
          this.chatId = data.chat_id;
          this.messages = data.messages;
          this.critique = data.critique;

          // Connect to websocket server of conversation
          Echo.private("chat." + this.chatId)
            .listen("MessageBroadcastEvent", (e) => {
              this.messages.push(e.message);
            })
            .listenForWhisper("typing", (e) => {
              this.isTyping = e.typing;

              // Remove isTyping indicator after 0.9s
              var self = this;
              setTimeout(function () {
                self.isTyping = false;
              }, 1000);
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
          this.isRetrievingMessages = false;
        });
    },

    sendMessage() {
      if (this.$refs.formMessage.validate()) {
        // Set isRetievingMessages to true
        this.isSendingMessages = true;

        // Retrieve current authenticated crituque id from session storage
        var critiqueId = sessionStorage.getItem("critiqueId") ?? null;

        // Retieve receiver
        var receiverId = this.$route.params.id;

        axios
          .post(`/api/critiques/${critiqueId}/messages`, {
            text: this.message,
            receiver_id: receiverId,
          })
          .then((response) => {
            let data = response.data;

            // Set messages to data
            this.pushMessageToMessages(data);
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
            this.isSendingMessages = false;
          });
      }
    },
  },
};
</script>
