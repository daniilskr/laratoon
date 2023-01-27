<template>
  <div class="user-notification">
    <div class="user-notification__image__wrapper">
      <div
        :style="`background: center / contain no-repeat url(${img})`"
        class="user-notification__image round-angles-small"
      />
    </div>
    <div class="user-notification__info">
      <h3 class="user-notification__info__title">{{ title }}</h3>
      <h4 class="user-notification__info__text">{{ text }}</h4>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps(["title", "text", "img"]);
</script>

<style scoped>
.user-notification {
  overflow: hidden;
  position: relative;
}

.user-notification {
  display: flex;
}

.user-notification__image__wrapper {
  z-index: 2;
  position: relative;
}

.user-notification__image {
  width: 60px;
  height: 60px;
  display: block;
}

.user-notification__info {
  z-index: 3;
  flex-grow: 1;
  text-align: left;
  padding: 0 15px;
}

.user-notification__info__title {
  font-weight: 500;
  font-size: 12px;
  line-height: 22px;
  color: #252636;
  margin: 0;
}

.user-notification__info__text {
  font-weight: 400;
  font-size: 14px;
  letter-spacing: 0.025em;
  line-height: 15px;
  color: #b1b1b1;
  margin: 0;
}
</style>
