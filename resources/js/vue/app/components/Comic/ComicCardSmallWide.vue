<template>
  <div class="comic-card-small-wide">
    <div class="comic-card-small-wide__cover">
      <div class="comic-card-small-wide__cover__image__wrapper">
        <div
          :style="`background: center / contain no-repeat url(${image.medium})`"
          class="comic-card-small-wide__cover__image round-angles-small"
        />
      </div>
      <div class="comic-card-small-wide__cover__info">
        <h3 class="comic-card-small-wide__cover__info__title">{{ title }}</h3>
        <h4 class="comic-card-small-wide__cover__info__author">{{ author }}</h4>
      </div>
    </div>
    <div class="comic-card-small-wide__content">
      <div class="comic-card-small-wide__content__image">
        <h3 class="comic-card-small-wide__content__title">{{ title }}</h3>
        <h4 class="comic-card-small-wide__content__author">{{ author }}</h4>
      </div>
      <div class="comic-card-small-wide__content__info">
        <div class="comic-card-small-wide__content__description">
          <p>{{ description }}</p>
        </div>
        <div class="comic-card-small-wide__content__statistics">
          <StatisticsLikesCount :likes="likes" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import StatisticsLikesCount from "@/components/Statistics/StatisticsLikesCount.vue";
defineProps<{
  title: string;
  author: string;
  description: string;
  likes: number;
  image: {
    medium: string;
  };
}>();
</script>

<style scoped>
.comic-card-small-wide {
  overflow: hidden;
  position: relative;
  height: 100px;
}

.comic-card-small-wide__content {
  z-index: 9;
  display: flex;
  text-align: center;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: white;
  opacity: 0;
  transition-delay: 80ms;
  text-align: left;
  box-sizing: border-box;
}

.comic-card-small-wide__content__image {
  text-align: center;
  width: 100px;
  height: 100px;
  display: flex;
  flex-flow: column;
  flex-grow: 0;
  flex-shrink: 0;
  justify-content: center;
}

.comic-card-small-wide__content__title {
  font-weight: 400;
  font-size: 13px;
  line-height: 16px;
  color: #000000;
  margin: 0;
}

.comic-card-small-wide__content__author {
  font-weight: 400;
  font-size: 13px;
  letter-spacing: 0.025em;
  line-height: 15px;
  color: #b1b1b1;
  margin: 0;
}

.comic-card-small-wide__content__info {
  z-index: 3;
  /* width: 286px; */
  height: 100px;
  text-align: left;
  padding: 0 15px;
  box-sizing: border-box;
  border-left: 1px solid #f5f5f5;
}

.comic-card-small-wide__content__description {
  font-style: normal;
  font-weight: normal;
  font-size: 13px;
  line-height: 16px;
  margin-top: 0px;
  color: #000000;
}

.comic-card-small-wide__content__statistics {
  position: absolute;
  bottom: 0;
  margin: 0;
  line-height: 15px;
  font-size: 15px;
  font-weight: 600;
  color: #000000;
}

.comic-card-small-wide:hover > .comic-card-small-wide__content {
  opacity: 1;
}

.comic-card-small-wide__cover {
  display: flex;
}

.comic-card-small-wide__cover__image__wrapper {
  z-index: 2;
  position: relative;
}

.comic-card-small-wide__cover__image {
  width: 100px;
  height: 100px;
  display: block;
}

.comic-card-small-wide__cover__info {
  z-index: 3;
  flex-grow: 1;
  height: 100px;
  text-align: left;
  padding: 0 15px;
}

.comic-card-small-wide__cover__info__title {
  font-weight: 500;
  font-size: 17px;
  line-height: 22px;
  color: #252636;
  margin: 0;
}

.comic-card-small-wide__cover__info__author {
  font-weight: 400;
  font-size: 13px;
  letter-spacing: 0.025em;
  line-height: 15px;
  color: #b1b1b1;
  margin: 0;
}
</style>
