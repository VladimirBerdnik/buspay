<template>
  <td :class="classes"
      @click="intentionAllowed ? $emit('activate') : null"
  >
    <slot/>
  </td>
</template>
<script>
export default {
  name:  'ActionCell',
  props: {
    itemType: {
      type:    String,
      default: null,
    },
    intention: {
      type:    String,
      default: null,
    },
  },
  computed: {
    /**
     * Whether action of component allowed or not.
     *
     * @return {boolean}
     */
    intentionAllowed() {
      if (!this.intention) {
        return true;
      }

      return this.policies.can(this.itemType, this.intention);
    },
    /**
     * Style classes of component.
     *
     * @return {string}
     */
    classes() {
      return this.intentionAllowed ? 'action-cell' : '';
    },
  },
};
</script>
