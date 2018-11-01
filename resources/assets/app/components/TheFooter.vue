<template>
  <v-footer color="indigo"
            dark
            app
  >
    <v-spacer/>
    <div>{{ new Date().getFullYear() }} - {{ $t('app.title') }}</div>
    <v-spacer/>
  </v-footer>
</template>

<script>
export default {
  name: 'TheFooter',
};
</script>
