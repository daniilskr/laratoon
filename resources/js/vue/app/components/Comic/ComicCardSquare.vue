<template>
  <div class="comic-card-square round-angles-small">
    <div class="comic-card-square__cover">
      <div class="comic-card-square__cover__background">
        <img :src="img" />
        <div class="comic-card-square__cover__background__shadow"></div>
      </div>
      <div class="comic-card-square__cover__front">
        <h3 class="comic-card-square__cover__front__title">{{ title }}</h3>
        <h4 class="comic-card-square__cover__front__author">{{ author }}</h4>
      </div>
    </div>
    <div class="comic-card-square__content">
      <div class="comic-card-square__content__info">
        <h3 class="comic-card-square__content__title">{{ title }}</h3>
        <h4 class="comic-card-square__content__author">{{ author }}</h4>
        <p class="comic-card-square__content__description">{{ description }}</p>
      </div>
      <div class="comic-card-square__content__statistics">
        <StatisticsLikesCount :likes="likes" />
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
  img: string;
}>();
</script>

<style scoped>
.comic-card-square {
  overflow: hidden;
  display: block;
  position: relative;
}

.comic-card-square__content {
  z-index: 9;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: white;
  opacity: 0;
  padding: 13px;
  text-align: left;
  box-sizing: border-box;
}

.comic-card-square__content__wrapper {
  width: 100%;
  height: 100%;
  position: relative;
}

.comic-card-square__content__title {
  font-weight: 400;
  font-size: 16px;
  line-height: 14px;
  color: #000000;
  margin: 0;
}

.comic-card-square__content__author {
  font-weight: 400;
  font-size: 13px;
  letter-spacing: 0.025em;
  line-height: 15px;
  color: #b1b1b1;
  margin: 0;
}

.comic-card-square__content__description {
  font-style: normal;
  font-weight: normal;
  font-size: 14px;
  line-height: 14px;
  color: #000000;
}

.comic-card-square__content__likes {
  position: absolute;
  bottom: 0;
  margin: 0;
  display: flex;
  align-items: flex-end;
}

.comic-card-square__content__likes__heart-icon-wrapper {
  padding-right: 5px;
  margin-bottom: -1px;
}

.comic-card-square__content__likes__number {
  line-height: 10px;
  font-size: 15px;
  font-weight: 600;
}

.comic-card-square > .comic-card-square__content,
.comic-card-square > .comic-card-square__cover {
  transition-delay: 80ms;
}

.comic-card-square:hover > .comic-card-square__cover {
  opacity: 0;
}

.comic-card-square:hover > .comic-card-square__content {
  opacity: 1;
}

.comic-card-square__cover__background {
  z-index: 2;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  position: relative;
}

.comic-card-square__cover__background:before {
  content: " ";
  display: block;
  width: 100%;
  padding-top: 100%;
}

.comic-card-square__cover__background > img {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
}

.comic-card-square__cover__background__shadow {
  position: absolute;
  width: 100%;
  height: 1px;
  bottom: -1px;
  box-shadow: 0px 0px 28px 54px rgba(0, 0, 0, 0.69);
  transform: matrix(1, 0, 0, -1, 0, 0);
}

.comic-card-square__cover__front {
  bottom: 0;
  width: 100%;
  z-index: 3;
  position: absolute;
}

.comic-card-square__cover__front__author,
.comic-card-square__cover__front__title {
  font-weight: 400;
  text-align: center;
  margin: 0;
}

.comic-card-square__cover__front__title {
  font-size: 16px;
  line-height: 12px;
  color: #ffffff;
}

.comic-card-square__cover__front__author {
  font-size: 13px;
  line-height: 25px;
  letter-spacing: 0.025em;
  color: #b1b1b1;
}
</style>
