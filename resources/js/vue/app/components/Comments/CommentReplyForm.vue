<template>
  <div v-if="currentUser" class="comment-reply-form">
    <div class="padding-r-15">
      <div
        :style="`background: center / contain no-repeat url(${currentUser?.avatar.medium})`"
        class="comment-reply-form__image round-angles-small"
      ></div>
    </div>
    <div class="comment-reply-form__content">
      <div class="comment-reply-form__content__input">
        <InputContenteditable
          :disabled="isPosting"
          v-model="commentText"
          focus-on-mount
          placeholder="Share your thoughts"
        />
      </div>
      <div class="comment-reply-form__buttons">
        <button @click="cancelButtonClicked()" class="comment-reply-form__buttons__cancel btn-reset-style">
          Cancel
        </button>
        <button
          @click="isValidCommentText && postButtonClicked()"
          :class="[
            'comment-reply-form__buttons__post btn-reset-style',
            { 'comment-reply-form__buttons__post--disabled': !isValidCommentText },
          ]"
        >
          Post
        </button>
      </div>
    </div>
    <div v-if="isPosting" class="comment-reply-form__disabled-overlay"></div>
  </div>
</template>

<script setup lang="ts">
import InputContenteditable from "@/components/Inputs/InputContenteditable.vue";
import axios from "axios";
import { ref, computed } from "vue";
import { BACKEND_URL } from "@/config";
import _ from "lodash";
import type Comment from "@/api/Resources/Types/Comment";
import { useAuth } from "@/composable/useAuth";
import { parseComment } from "@/api/Resources/Types/Comment";

const props = defineProps<{
  parentCommentId: number;
}>();

const emit = defineEmits<{
  (e: "replyPosted", comment: Comment): void;
  (e: "cancelButtonClicked"): void;
}>();

const { currentUser } = useAuth();

const commentText = ref("");

const isValidCommentText = computed(() => _.trim(commentText.value).length > 0);

const clearText = () => {
  commentText.value = "";
};

const isPosting = ref(false);
const postReply = (text: string, parentCommentId: number) => {
  if (isPosting.value) {
    // already posting
    return;
  }

  isPosting.value = true;

  axios
    .post(`${BACKEND_URL}/api/comments/${parentCommentId}/replies`, {
      comment_text: text,
    })
    .then((response) => {
      if (response.status === 201) {
        clearText();
        emit("replyPosted", parseComment(response.data.data));
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
  postReply(commentText.value, props.parentCommentId);
};

const cancelButtonClicked = () => {
  clearText();
  emit("cancelButtonClicked");
};
</script>

<style scoped>
.comment-reply-form {
  display: flex;
  margin-bottom: 1px;
  opacity: 1;
  position: relative;
}

.comment-reply-form__image {
  width: 24px;
  height: 24px;
}

.comment-reply-form__content {
  flex-grow: 1;
  display: flex;
  min-width: 0;
  flex-direction: column;
  align-items: end;
  background: white;
  transition: 0.32s;
}

.comment-reply-form__content__input {
  width: 100%;
}

.comment-reply-form__post-icon {
  opacity: 0.7;
  transition: opacity ease 0.1 s;
  fill: #99a2ad;
}

.comment-reply-form__buttons {
  padding-top: 5px;
}

.comment-reply-form__buttons__cancel {
  cursor: pointer;
  user-select: none;
}

.comment-reply-form__buttons__post {
  cursor: pointer;
  user-select: none;
  border-radius: 3px;
  background: #2dbcdc;
  color: white;
}

.comment-reply-form__buttons__post--disabled {
  background: #e7e7e7;
  color: gray;
  cursor: default;
}

.comment-reply-form__buttons__cancel,
.comment-reply-form__buttons__post {
  padding: 8px 20px;
  text-transform: uppercase;
  font-size: 12px;
}

.comment-reply-form__buttons__cancel {
  color: #505050;
  cursor: pointer;
  user-select: none;
}

.comment-reply-form__disabled-overlay {
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: 2;
  pointer-events: all;
  background: white;
  opacity: 0.8;
}
</style>
