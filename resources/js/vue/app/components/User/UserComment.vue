<template>
  <div class="comment-item">
    <div class="comment-item__content">
      <div class="comment-item__content__up">
        <div class="comment-item__content__up__head">
          <div class="comment-item__content__up__head__commented-on">
            Commented on
            <span class="comment-item__content__up__head__commented-on_commentable-name">
              {{ commentableOwnerLabel }}
            </span>
          </div>
          <div class="comment-item__content__up__head__published-time">
            {{ formatDateLongAgo(publishedAt) }}
          </div>
        </div>
        <div class="comment-item__content__up__text">
          {{ text }}
        </div>
      </div>
      <div class="comment-item__content__down">
        <div class="comment-item__content__down__statistics">
          <StatisticsLikesCount :likes="likes" />
          <StatisticsCommentsCount :comments="replies" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import StatisticsCommentsCount from "@/components/Statistics/StatisticsCommentsCount.vue";
import StatisticsLikesCount from "@/components/Statistics/StatisticsLikesCount.vue";
import { formatDateLongAgo } from "@/util/date";

defineProps<{
  id: number;
  commentableOwnerLabel: string;
  publishedAt: string;
  text: string;
  likes: number;
  replies: number;
}>();
</script>

<style scoped>
.comment-item {
  display: flex;
  margin-bottom: 1px;
  opacity: 1;
}

.comment-item__content {
  flex-grow: 1;
  display: flex;
  justify-content: space-between;
  flex-direction: column;
  align-items: stretch;
  background: white;
  transition: 0.32s;
}

.comment-item__content__up {
  display: flex;
  flex-direction: column;
}

.comment-item__content__up__text {
  text-align: left;
  font-size: 14px;
  line-height: 22px;
  padding-top: 7px;
}

.comment-item__content__up__head {
  display: flex;
}

.comment-item__content__up__head__commented-on {
  color: #2c3e50;
  line-height: 14px;
  font-size: 12px;
  font-weight: 600;
}

.comment-item__content__up__head__commented-on_commentable-name {
  color: rgb(99, 171, 238);
}

.comment-item__content__up__head__published-time {
  color: #b1b1b1;
  font-size: 12px;
  line-height: 15px;
  padding-left: 5px;
}

.comment-item__content__down {
  padding-top: 5px;
  display: flex;
}

.comment-item__content__down__statistics {
  display: flex;
}

.comment-item__content__down__statistics > * {
  padding-right: 15px;
}
</style>
