<template>
  <svg
    class="svg-icon"
    :width="width"
    :height="height"
    fill="none"
    viewBox="0 0 14 16"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path
      fill-rule="evenodd"
      clip-rule="evenodd"
      d="M5.5 9C7.433 9 9 7.433 9 5.5C9 3.567 7.433 2 5.5 2C3.567 2 2 3.567 2 5.5C2 7.433 3.567 9 5.5 9ZM5.5 11C8.53757 11 11 8.53757 11 5.5C11 2.46243 8.53757 0 5.5 0C2.46243 0 0 2.46243 0 5.5C0 8.53757 2.46243 11 5.5 11Z"
      :fill="fillColor"
    />
    <path
      fill-rule="evenodd"
      clip-rule="evenodd"
      d="M12.9396 14.0604C12.3538 14.6462 11.404 14.6462 10.8182 14.0604L8.93957 12.1818C8.35378 11.596 8.35378 10.6462 8.93957 10.0604C9.52535 9.47465 10.4751 9.47465 11.0609 10.0604L12.9396 11.9391C13.5254 12.5249 13.5254 13.4746 12.9396 14.0604Z"
      :fill="fillColor"
    />
  </svg>
</template>

<script setup lang="ts">
defineProps(["width", "height", "fillColor"]);
</script>
