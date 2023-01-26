<template>
  <svg
    class="svg-icon"
    :width="width"
    :height="height"
    viewBox="0 0 12 11"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
  >
    <path
      d="M0.0851746 3.67565C0.577784 5.76832 5.99648 11 5.99648 11C5.99648 11 11.4965 5.25788 11.9078 3.67565C12.3191 2.09343 11.3339 0.0574885 9.36342 0.00183924C7.98017 -0.0372269 6.9001 0.536644 5.99648 2.62492C5.42227 1.57075 4.35619 -0.0122512 2.9595 0.0012248C1.32721 0.0169741 -0.407435 1.58298 0.0851746 3.67565Z"
      :fill="fillColor"
    />
  </svg>
</template>

<script setup lang="ts">
defineProps(["width", "height", "fillColor"]);
</script>
