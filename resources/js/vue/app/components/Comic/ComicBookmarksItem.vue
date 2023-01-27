<template>
  <div class="comic-bookmark-item">
    <div class="comic-bookmark-item__icon">
      <IconBookmark width="21px" height="34px" :fill-color="color" />
    </div>
    <div class="comic-bookmark-item__info">
      <div class="comic-bookmark-item__info__left padding-x-5">
        <div class="comic-bookmark-item__info__left__episode">
          {{ episode }}
        </div>
        <div class="comic-bookmark-item__info__left__page">P {{ page }}</div>
      </div>
      <div class="comic-bookmark-item__info__right padding-x-10">
        <div class="comic-bookmark-item__info__right__note text-left">
          {{ note }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import IconBookmark from "@/components/Icons/IconBookmark.vue";
defineProps(["episode", "page", "color", "note"]);
</script>

<style scoped>
.comic-bookmark-item {
  display: flex;
  margin-bottom: 1px;
  opacity: 1;
}

.comic-bookmark-item__icon {
  padding: 5px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: left;
  flex-shrink: 0;
}

.comic-bookmark-item__info {
  flex-grow: 1;
  display: flex;
  justify-content: start;
  align-items: stretch;
  transition: 0.32s;
}

.comic-bookmark-item:hover {
  background: #f5f5f5;
}

.comic-bookmark-item__info__left {
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: left;
  flex-shrink: 0;
}

.comic-bookmark-item__info__left__episode {
  font-weight: 500;
  font-size: 15px;
  line-height: 16px;
  color: black;
}

.comic-bookmark-item__info__left__page {
  color: #b1b1b1;
  letter-spacing: 1px;
  font-weight: 500;
  font-size: 11px;
  line-height: 16px;
}

.comic-bookmark-item__info__right {
  display: flex;
  flex-direction: column;
  justify-content: center;
  text-align: right;
}

.comic-bookmark-item__info__right__note {
  color: #3d3d3d;
  font-size: 14px;
}
</style>
