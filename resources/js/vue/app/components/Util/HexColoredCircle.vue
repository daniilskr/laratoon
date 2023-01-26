<template>
  <div class="util-hex-colored-circle" :style="`background:` + getValidHexColor(color, DEFAULT_HEX_COLOR)"></div>
</template>

<script setup lang="ts">
import { getValidHexColor } from "@/util/colors";
/* eslint-disable-next-line @typescript-eslint/no-unused-vars */
const props = defineProps<{ color: string }>();
const DEFAULT_HEX_COLOR = "#3D3D3D";
</script>

<style scoped>
.util-hex-colored-circle {
  padding: 5px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
}
</style>
