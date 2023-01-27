<template>
  <div class="comic-card-small-tall">
    <div class="comic-card-small-tall__info">
      <h3 class="comic-card-small-tall__info__title">{{ title }}</h3>
      <h4 class="comic-card-small-tall__info__author">{{ author }}</h4>
    </div>
    <div class="comic-card-small-tall__body">
      <div class="comic-card-small-tall__body__overlay round-angles-small">
        <div class="comic-card-small-tall__body__overlay__main">
          {{ main }}
        </div>
        <div class="comic-card-small-tall__body__overlay__additional">
          {{ additional }}
        </div>
      </div>
      <div
        :style="`background: center / contain no-repeat url(${image.medium})`"
        class="comic-card-small-tall__body__image round-angles-small"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  title: string;
  author: string;
  main: string;
  additional: string;
  image: {
    medium: string;
  };
}>();
</script>

<style scoped>
.comic-card-small-tall {
  overflow: hidden;
  position: relative;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.comic-card-small-tall:hover > .comic-card-small-tall__content {
  opacity: 1;
}

.comic-card-small-tall:hover .comic-card-small-tall__info__title {
  text-decoration: underline;
}

.comic-card-small-tall:hover .comic-card-small-tall__body__overlay {
  opacity: 0;
}

.comic-card-small-tall {
  display: flex;
}

.comic-card-small-tall__body {
  position: relative;
}

.comic-card-small-tall__body:before {
  content: " ";
  display: block;
  width: 100%;
  padding-top: 100%;
}

.comic-card-small-tall__body__image {
  display: block;
}

.comic-card-small-tall__body__image,
.comic-card-small-tall__body__overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  background: rgba(0, 0, 0, 26%);
}

.comic-card-small-tall__body__overlay {
  z-index: 2;
  display: flex;
  flex-direction: column;
  color: white;
  text-transform: uppercase;
}
.comic-card-small-tall__body__overlay__main {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 34px;
  font-weight: 700;
}

.comic-card-small-tall__body__overlay__additional {
  font-weight: 500;
  font-size: 14px;
}

.comic-card-small-tall__info {
  flex-grow: 1;
  text-align: left;
  padding: 10px 0;
}

.comic-card-small-tall__info__title {
  font-weight: 500;
  font-size: 19px;
  line-height: 20px;
  color: #252636;
  margin: 0;
}

.comic-card-small-tall__info__author {
  font-weight: 400;
  font-size: 13px;
  letter-spacing: 0.025em;
  line-height: 19px;
  color: #252636;
  margin: 0;
}
</style>
