<template>
  <div class="input-search round-angles-small">
    <InputTextboxField :placeholder="placeholder">
      <div class="input-search__search-icon-wrapper">
        <IconSearch :width="14" :height="16" :fill-color="'#A5A0A0'" />
      </div>
    </InputTextboxField>
  </div>
</template>

<script setup lang="ts">
import InputTextboxField from "@/components/Inputs/InputTextboxField.vue";
import IconSearch from "@/components/Icons/IconSearch.vue";

defineProps({
  placeholder: {
    type: String,
    default: "Search...",
  },
});
</script>

<style scoped>
.input-search {
  position: relative;
}

.input-search__search-icon-wrapper {
  position: absolute;
  right: 0;
  top: 0;
  height: 100%;
  display: flex;
  align-items: center;
  padding: 0 10px;
}
</style>
