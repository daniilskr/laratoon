<template>
  <div>
    <div :class="['comment-item', { 'comment-item--root': isRoot }]">
      <div class="comment-item__avatar padding-r-15">
        <div
          :style="`background: center / contain no-repeat url(${author.avatar.medium})`"
          class="comment-item__avatar__image round-angles-small"
        ></div>
      </div>

      <div class="comment-item__content">
        <div class="comment-item__content__up">
          <div class="comment-item__content__up__head">
            <div class="comment-item__content__up__head__left">
              <div class="comment-item__content__up__head__left__name">
                {{ author.fullName }}
              </div>
              <div class="comment-item__content__up__head__left__published-time">
                {{ publishedAt }}
              </div>
            </div>
            <div class="comment-item__content__up__head__right">
              <!-- <div @click="editMode = true" class="comment-item__content__up__head__right__edit">Edit</div> -->
            </div>
          </div>
          <div class="comment-item__content__up__text">
            <!-- eslint-disable-next-line vue/require-v-for-key -->
            <div v-for="line in lines" class="comment-item__content__up__text-line">
              {{ line }}
            </div>
          </div>
        </div>
        <div class="comment-item__content__down">
          <div class="comment-item__content__down__likes">
            <LikeButton
              :likeable-id="props.likeableId"
              :likes-cached-count="props.likesCachedCount"
              :is-initially-liked="props.isLikedByUser"
            />
          </div>
          <div @click="replyFormShown = true" class="comment-item__content__down__reply-button">reply</div>
        </div>
        <CommentReplies
          @cancel-button-clicked="replyFormShown = false"
          @reply-posted="onReplyPosted"
          :root-child-comments-cached-count="rootChildCommentsCachedCount"
          :reply-form-shown="replyFormShown"
          :root-comment-id="props.rootCommentId ? props.rootCommentId : props.id"
          :parent-comment-id="props.id"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import LikeButton from "@/components/LikeButton.vue";
import CommentReplies from "@/components/Comments/CommentReplies.vue";
import _ from "lodash";
import type Comment from "@/api/Resources/Types/Comment";

const props = defineProps<{
  id: number;
  text: string;
  publishedAt: string;

  author: {
    id: number;
    fullName: string;
    avatar: {
      medium: string;
    };
  };

  commentableId: number;

  likesCachedCount: number;
  likeableId: number;
  isLikedByUser: boolean;

  rootChildCommentsCachedCount: number;
  rootCommentId: null | number;
  parentCommentId: null | number;
}>();

const emit = defineEmits<{
  (e: "replyPosted", comment: Comment): void;
}>();

const isRoot = computed(() => _.isNull(props.rootCommentId));

/** <REPLIES> */
const replyFormShown = ref(false);

const onReplyPosted = (reply: Comment) => {
  replyFormShown.value = false;
  emit("replyPosted", reply);
};
/** </ REPLIES> */

const lines = computed(() => props.text.split(/(?:\r\n|\r|\n)/));
</script>

<style scoped>
.comment-item {
  display: flex;
  margin-bottom: 1px;
  opacity: 1;
}

.comment-item--root > .comment-item__avatar > .comment-item__avatar__image {
  width: 34px;
  height: 34px;
}

.comment-item__avatar__image {
  width: 24px;
  height: 24px;
  background: rgb(99, 171, 238);
}

.comment-item__content {
  flex: 1;
  min-width: 0;
  display: flex;
  justify-content: space-between;
  flex-direction: column;
  align-items: stretch;
  transition: 0.32s;
}

.comment-item__content__up {
  display: flex;
  flex-direction: column;
}

.comment-item__content__up__head {
  display: flex;
  justify-content: space-between;
}

.comment-item__content__up__head__right,
.comment-item__content__up__head__left {
  display: flex;
}

.comment-item__content__up__head__right__edit {
  font-size: 14px;
  line-height: 14px;
}

.comment-item__content__up__text {
  word-wrap: break-word;
  min-width: 0;
}

.comment-item__content__up__text-line {
  text-align: left;
  font-size: 14px;
  line-height: 22px;
  padding-top: 3px;
}

.comment-item__content__up__head__left__name {
  color: #2c3e50;
  line-height: 14px;
}

.comment-item__content__up__head__left__published-time {
  color: #b1b1b1;
  font-size: 12px;
  line-height: 16px;
  padding-left: 5px;
}

.comment-item__content__down {
  display: flex;
  padding-top: 8px;
}

.comment-item__content__down__likes {
  display: flex;
}

.comment-item__content__down__reply-button {
  font-weight: 600;
  font-size: 12px;
  font-style: normal;
  text-transform: uppercase;
  margin: 0;
  cursor: pointer;
  user-select: none;
  margin-left: 10px;
  color: #505050;
}
</style>
