<template>
  <div class="comic-tag-item">
    <div class="comic-tag-item__name">
      {{ name }}
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps(["id", "name"]);
</script>

<style scoped>
.comic-tag-item {
  background: #3d3d3d;
  border-radius: 6px;
  padding: 1px 11px;
  /* border: solid 1px #3D3D3D; */
  transition-duration: 0.12s;
}

.comic-tag-item__name {
  text-transform: uppercase;
  color: #ffffff;
  font-size: 11px;
  transition-duration: 0.12s;
}

.comic-tag-item:hover {
  background: #565555;
}

/* .comic-tag-item:hover {
    background: #FFFFFF;
  }

  .comic-tag-item:hover .comic-tag-item__name {
    color: #3D3D3D
  } */
</style>
