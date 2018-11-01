<template>
  <div v-if="profile">
    <h1>Панель управления</h1>
    <h2>{{ `Привет, ${profile.role.name} ${profile.first_name}!` }}</h2>
  </div>
</template>

<script>
import ProfileService from '../services/ProfileService';

export default {
  name:     'Cabinet',
  computed: {
    profile: () => ProfileService.getProfile(),
  },
  async mounted() {
    await ProfileService.readProfile();
  },
};
</script>
