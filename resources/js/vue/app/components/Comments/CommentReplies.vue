<template>
  <div class="comment-replies">
    <div v-if="replyFormShown" class="comment-replies__reply-form">
      <CommentReplyForm
        @reply-posted="onReplyPosted"
        @cancel-button-clicked="emit('cancelButtonClicked')"
        :parent-comment-id="props.parentCommentId"
      />
    </div>
    <div v-if="isRoot">
      <div v-if="props.rootChildCommentsCachedCount > 0" class="comment-replies__replies">
        <div class="text-left">
          <p @click="!isLoading && (repliesShown = !repliesShown)" class="comment-replies__replies__show-button">
            {{ repliesShown ? "Hide" : "Show" }}
            {{ pluralizeWithNumber(props.rootChildCommentsCachedCount, "reply", "replies") }}
          </p>
        </div>
      </div>
    </div>
    <div v-if="repliesShown && props.rootCommentId" class="comment-replies__items">
      <div>
        <p
          v-if="!isLoading && replies.doesCursorHaveMore()"
          @click="fetchNextPage"
          class="comment-replies__show-more-button"
        >
          Show more replies
        </p>
      </div>
      <div v-if="isLoading" class="comment-replies__loading">
        <LoadingSpinner :delay-ms="300" />
      </div>
      <div class="comment-replies__list">
        <TransitionGroup name="list">
          <div
            v-for="reply in [...replies.items].reverse()"
            :key="reply.id"
            class="comment-replies__list__item-wrapper padding-y-15"
          >
            <CommentItem
              @reply-posted="onReplyPosted"
              :commentable-id="reply.commentable.id"
              :parent-comment-id="reply.parentCommentId"
              :root-comment-id="reply.rootCommentId"
              :root-child-comments-cached-count="reply.rootChildCommentsCachedCount ?? 0"
              :id="reply.id"
              :author="reply.author"
              :published-at="formatDateLongAgo(reply.publishedAt)"
              :text="reply.text"
              :likes-cached-count="reply.likeable.likesCachedCount"
              :likeable-id="reply.likeable.id"
              :is-liked-by-user="reply.likeable.isLikedByUser"
            />
          </div>
        </TransitionGroup>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, watch, ref } from "vue";
import { formatDateLongAgo } from "@/util/date";
import { pluralizeWithNumber } from "@/util/strings";
import CommentItem from "@/components/Comments/CommentItem.vue";
import LoadingSpinner from "@/components/Util/LoadingSpinner.vue";
import CommentReplyForm from "@/components/Comments/CommentReplyForm.vue";
import { useReplies } from "@/composable/comments/useReplies";
import type Comment from "@/api/Resources/Types/Comment";

const props = defineProps<{
  parentCommentId: number;
  rootCommentId: number;
  replyFormShown: boolean;
  rootChildCommentsCachedCount: number;
}>();

const emit = defineEmits<{
  (e: "replyPosted", reply: Comment): void;
  (e: "cancelButtonClicked"): void;
}>();

const repliesShown = ref(false);
const isRoot = computed(() => props.rootCommentId === props.parentCommentId);

// prettier-ignore
const {
  replies, fetchFirstPage, fetchNextPage,
  onNewUserPostedReply, loadedRoot, isLoading,
} = useReplies();

const onReplyPosted = (reply: Comment) => {
  if (!repliesShown.value) {
    repliesShown.value = true;
  }

  if (isRoot.value) {
    onNewUserPostedReply(reply);
  }

  emit("replyPosted", reply);
};

const ensureRepliesLoaded = () => {
  if (loadedRoot.value !== props.parentCommentId) {
    if (props.parentCommentId) fetchFirstPage(props.parentCommentId);
  }
};
const commentsHaveToBeLoaded = computed(() => isRoot.value && repliesShown.value);
watch(commentsHaveToBeLoaded, () => {
  if (commentsHaveToBeLoaded.value) ensureRepliesLoaded();
});
</script>

<style scoped>
/* I kinda do not like this animation, so mayby I`ll come up with something better later */

/* .list-move,
.list-enter-active,
.list-leave-active {
  transition: all 1s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
}

.list-leave-active {
  position: absolute;
} */

.comment-replies {
  display: flex;
  flex-direction: column;
  align-items: stretch;
}

.comment-replies__loading {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.comment-replies__list {
  overflow: hidden;
}

.comment-replies__list__item-wrapper {
  border-top: solid 1px #f5f5f5;
  background: white;
}

.comment-replies__replies {
  padding-top: 5px;
}

.comment-replies__show-more-button,
.comment-replies__replies__show-button {
  color: rgb(99, 171, 238);
  font-size: 14px;
  cursor: pointer;
  user-select: none;
  display: inline;
}

.comment-replies__reply-form {
  padding-top: 9px;
}
</style>
