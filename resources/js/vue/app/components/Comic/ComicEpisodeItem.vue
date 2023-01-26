<template>
  <div :class="['comic-episode-item', { 'comic-episode-item-seen': isSeen }]">
    <div :style="`background: center / contain no-repeat url(${poster.medium}`" class="comic-episode-item__image"></div>
    <div class="comic-episode-item__info">
      <div class="comic-episode-item__info__left padding-x-10">
        <div class="comic-episode-item__info__left__title">
          {{ title }}
        </div>
        <div class="comic-episode-item__info__left__date">
          {{ formatDateLongAgo(date) }}
        </div>
      </div>
      <div class="comic-episode-item__info__right padding-x-10">
        <div class="comic-episode-item__info__right__number">#{{ number }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { formatDateLongAgo } from "@/util/date";

defineProps<{
  title: string;
  date: string;
  number: number | string;
  poster: {
    medium: string;
  };
  isSeen: boolean;
}>();
</script>

<style scoped>
.comic-episode-item {
  display: flex;
  margin-bottom: 1px;
  opacity: 1;
}

.comic-episode-item-seen .comic-episode-item__image {
  opacity: 0.3;
}

.comic-episode-item-seen .comic-episode-item__info__left__title,
.comic-episode-item-seen .comic-episode-item__info__right__number {
  color: #b1b1b1;
}

.comic-episode-item__image {
  flex-shrink: 0;
  width: 80px;
  height: 80px;
  background: rgb(99, 171, 238);
}

.comic-episode-item__info {
  flex-grow: 1;
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  background: white;
  transition: 0.32s;
}

.comic-episode-item:hover .comic-episode-item__info {
  background: #f5f5f5;
}

.comic-episode-item__info__left {
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: left;
}

.comic-episode-item__info__left__title {
  font-weight: 500;
  font-size: 17px;
  line-height: 16px;
  color: black;
}

.comic-episode-item__info__left__date {
  color: #b1b1b1;
  letter-spacing: 1px;
  font-weight: 500;
  font-size: 11px;
  line-height: 16px;
}

.comic-episode-item__info__right {
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: right;
}

.comic-episode-item__info__right__number {
  color: #3d3d3d;
  font-size: 17px;
}
</style>
