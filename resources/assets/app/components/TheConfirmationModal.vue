<template>
  <v-dialog
    :value="visible"
    max-width="400"
    persistent
    @input="close"
  >
    <v-layout
      align-center
      justify-center
    >
      <v-flex xs12>
        <v-card class="elevation-12">
          <v-toolbar dark>
            <v-toolbar-title>
              {{ title || $t('common.titles.confirmAction') }}
            </v-toolbar-title>
            <v-spacer/>
          </v-toolbar>

          <v-card-text>{{ message }}</v-card-text>

          <v-card-actions>
            <v-layout row
                      wrap
                      justify-end
            >
              <v-spacer/>
              <v-btn color="default"
                     @click="close"
              >
                {{ $t('common.buttons.close') }}
              </v-btn>
              <v-btn color="primary"
                     @click="confirm"
              >
                {{ $t('common.buttons.ok') }}
              </v-btn>
            </v-layout>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-dialog>
</template>

<script>
export default {
  name:  'TheConfirmationModal',
  props: {
    visible: {
      type:    Boolean,
      default: false,
    },
    title: {
      type:    String,
      default: '',
    },
    message: {
      type:    String,
      default: '',
    },
  },
  methods: {
    /**
     * Closes modal window.
     */
    close() {
      this.$emit('close', false);
    },
    /**
     * Closes modal window and confirms action.
     */
    confirm() {
      this.$emit('close', true);
    },
  },
};
</script>
