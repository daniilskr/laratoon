<template>
  <div class="comic-character-card">
    <div :style="`background: center / contain no-repeat url(${img})`" class="comic-character-card__image"></div>
    <div class="comic-character-card__name padding-t-15">
      {{ name }}
    </div>
    <div class="comic-character-card__note">
      {{ note }}
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  id: number;
  name: string;
  note: string;
  img: string;
}>();
</script>

<style scoped>
.comic-character-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 3px;
  height: 100%;
}

.comic-character-card:hover {
  background: #f5f5f5;
}

.comic-character-card__image {
  width: 80px;
  height: 80px;
  background: rgb(99, 171, 238);
}

.comic-character-card__name {
  font-weight: 500;
  font-size: 15px;
  line-height: 16px;
  color: black;
}

.comic-character-card__note {
  color: #b1b1b1;
  letter-spacing: 1px;
  font-weight: 500;
  font-size: 11px;
  line-height: 16px;
}
</style>
