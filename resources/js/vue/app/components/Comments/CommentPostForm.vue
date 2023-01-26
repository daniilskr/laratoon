<template>
  <div v-if="currentUser" class="comment-post-form">
    <div class="padding-r-15">
      <div
        :style="`background: center / contain no-repeat url(${currentUser?.avatar.medium})`"
        class="comment-post-form__image round-angles-small"
      ></div>
    </div>
    <div class="comment-post-form__content">
      <div class="comment-post-form__content__input">
        <InputContenteditable :disabled="isPosting" v-model="commentText" placeholder="Share your thoughts" />
      </div>
      <div class="comment-post-form__content__post-button--wrapper">
        <button
          @click="isValidCommentText && postButtonClicked()"
          :class="[
            'comment-post-form__content__post-button btn-reset-style',
            { 'comment-post-form__content__post-button--enabled': isValidCommentText },
          ]"
        >
          <IconPaperPlane class="comment-post-form__post-icon" width="22" height="22" direction="right" />
        </button>
      </div>
    </div>
    <div v-if="isPosting" class="comment-post-form__disabled-overlay"></div>
  </div>
</template>

<script setup lang="ts">
import InputContenteditable from "@/components/Inputs/InputContenteditable.vue";
import IconPaperPlane from "@/components/Icons/IconPaperPlane.vue";
import axios from "axios";
import { ref, watch, computed } from "vue";
import _ from "lodash";
import { BACKEND_URL } from "@/config";
import type Comment from "@/api/Resources/Types/Comment";
import { useAuth } from "@/composable/useAuth";
import { parseComment } from "@/api/Resources/Types/Comment";

const props = defineProps<{
  commentableId: number;
}>();

const emit = defineEmits<{
  (e: "commentPosted", comment: Comment): void;
}>();

const { currentUser } = useAuth();

const commentText = ref("");

const isValidCommentText = computed(() => _.trim(commentText.value).length > 0);

const clearText = () => {
  commentText.value = "";
};

const onCommentableChanged = () => {
  clearText();
};

watch(
  () => props.commentableId,
  () => onCommentableChanged()
);

const isPosting = ref(false);
const postComment = (text: string, commentable: number) => {
  if (isPosting.value) {
    // already posting
    return;
  }

  isPosting.value = true;

  axios
    .post(`${BACKEND_URL}/api/commentables/${commentable}/comments`, {
      comment_text: text,
    })
    .then((response) => {
      if (response.status === 201) {
        clearText();
        emit("commentPosted", parseComment(response.data.data));
      }
    })
    .catch((err) => {
      console.error(err);
    })
    .then(() => {
      isPosting.value = false;
    });
};

const postButtonClicked = () => {
  postComment(commentText.value, props.commentableId);
};
</script>

<style scoped>
.comment-post-form {
  display: flex;
  margin-bottom: 1px;
  opacity: 1;
  position: relative;
}

.comment-post-form__image {
  width: 34px;
  height: 34px;
}

.comment-post-form__content {
  flex-grow: 1;
  display: flex;
  min-width: 0;
  justify-content: space-between;
  flex-direction: row;
  align-items: stretch;
  background: white;
  transition: 0.32s;
}

.comment-post-form__content__input {
  flex: 1;
  min-width: 0;
}

.comment-post-form__content__post-button .comment-post-form__post-icon {
  opacity: 0.3;
  transition: opacity ease 0.1 s;
  fill: #99a2ad;
}

.comment-post-form__content__post-button--enabled .comment-post-form__post-icon {
  opacity: 0.7;
}

.comment-post-form__content__post-button--enabled:hover {
  cursor: pointer;
  user-select: none;
}
.comment-post-form__content__post-button--enabled:hover .comment-post-form__post-icon {
  opacity: 1;
}

.comment-post-form__disabled-overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: 2;
  pointer-events: all;
  background: white;
  opacity: 0.8;
}
</style>
