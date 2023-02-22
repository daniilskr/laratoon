<template>
    <svg
      class="svg-icon"
      :width="width"
      :height="height"
      viewBox="0 0 16 15"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
        <path
            d="M7.99908 1.48836L9.44197 5.53381L9.55685 5.85591L9.89869 5.86564L14.192 5.98779L10.7904 8.61017L10.5196 8.81897L10.616 9.14708L11.8265 13.268L8.28135 10.8433L7.99908 10.6502L7.71681 10.8433L4.17163 13.268L5.38217 9.14708L5.47855 8.81897L5.20772 8.61017L1.80614 5.98779L6.09947 5.86564L6.44131 5.85591L6.55619 5.53381L7.99908 1.48836Z"
            :fill="fillColor"
            :stroke="strokeColor"
        />
    </svg>

  </template>
  
  <script setup lang="ts">
  defineProps(["width", "height", "fillColor", "strokeColor"]);
  </script>
  