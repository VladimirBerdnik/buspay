<template>
  <v-dialog
    :value="true"
    max-width="360"
    persistent
  >
    <v-layout
      align-center
      justify-center
    >
      <v-flex xs12>
        <v-card class="elevation-12">
          <v-toolbar dark>
            <v-icon>hourglass_empty</v-icon>
            <v-toolbar-title>{{ $t('common.titles.preparingData') }}</v-toolbar-title>
          </v-toolbar>
          <v-list>
            <template v-for="step in steps">
              <v-subheader :key="step.name"
                           class="px-4"
              >
                {{ step.text }}
                <v-spacer/>
                <v-progress-circular v-if="!step.ready"
                                     indeterminate
                                     color="primary"
                />
                <v-icon v-else>done</v-icon>
              </v-subheader>
            </template>
          </v-list>
        </v-card>
      </v-flex>
    </v-layout>
  </v-dialog>
</template>

<script>
export default {
  name:  'TheSplashScreen',
  props: {
    steps: {
      type:    Object,
      default: () => {},
    },
  },
};
</script>
