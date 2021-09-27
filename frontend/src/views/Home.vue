<template>
  <div>
    {{ test }}
  </div>
</template>

<script>
import { ref } from "@vue/composition-api";

export default {
  name: "Home",
  setup(props, { root }) {
    const test = ref("Test String");

    return {
      test,
    };
  },
};
</script>

<style>
</style>