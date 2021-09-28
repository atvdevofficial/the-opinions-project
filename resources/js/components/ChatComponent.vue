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
      <v-list-item-content>
        <v-list-item-title>
          <div>Elen Mac Doe</div>
          <div class="caption font-italic">elenmacdoe</div>
        </v-list-item-title>
      </v-list-item-content>

      <v-list-item-action>
        <v-list-item-action-text>
          <v-avatar color="#FFEAB1" size="40">
            <box-icon name="user" size="sm"></box-icon>
          </v-avatar>
        </v-list-item-action-text>
      </v-list-item-action>
    </v-list-item>

    <div class="pa-1 caption font-italic text-center reminder">
      Messages are automatically deleted after 24 hours
    </div>

    <!-- <v-row no-gutters class="pl-4 pt-2">
      <v-col cols="12"> Elen Mac Doe </v-col>
      <v-col cols="12" class="caption"> elanmaedoe </v-col>
    </v-row> -->

    <!-- Start of Body -->
    <v-main>
      <!--  -->
    </v-main>

    <v-footer app color="transparent" height="72" inset>
      <v-text-field
        background-color="grey lighten-1"
        dense
        flat
        hide-details
        rounded
        solo
      ></v-text-field>
    </v-footer>
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

<style lang="scss" scoped>
.wrap-text {
  -webkit-line-clamp: unset !important;
}
.reminder {
    background-color: #FFEAB1;
}
</style>
