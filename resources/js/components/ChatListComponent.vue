<template>
  <div>
    <!-- Start of Toolbar -->
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
    <!-- End of Toolbar -->

    <!-- Start of Body -->
    <v-main>
      <v-container min-width="1366px">
        <div class="caption font-italic text-center">
          Messages are automatically deleted after 24 hours
        </div>
        <v-list two-line>
          <v-list-item
            v-for="index in [1, 2, 3]"
            :key="index"
            to="/chat/username"
          >
            <v-list-item-avatar color="#FFEAB1">
              <box-icon name="user" size="sm"></box-icon>
            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title>
                <div>Elen Mac Doe</div>
                <div class="caption font-italic">
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit. At,
                  voluptate.
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
  name: "ChatListComponent",
  data() {
    return {};
  },
};
</script>
