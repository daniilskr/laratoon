<template>
  <div class="commentable-comments">
    <div v-if="(!isLoading || comments.items.length > 0) && props.commentableId" class="commentable-comments__items">
      <div class="commentable-comments__comment-post-form-wrapper padding-y-15">
        <CommentPostForm :commentable-id="props.commentableId" @comment-posted="onCommentPosted" />
      </div>
      <div v-for="comment in comments.items" :key="comment.id" class="commentable-comments__item-wrapper padding-y-15">
        <CommentItem
          @reply-posted="onReplyPosted"
          :commentable-id="comment.commentable.id"
          :parent-comment-id="null"
          :root-comment-id="null"
          :root-child-comments-cached-count="comment.rootChildCommentsCachedCount ?? 0"
          :id="comment.id"
          :author="comment.author"
          :published-at="formatDateLongAgo(comment.publishedAt)"
          :text="comment.text"
          :likes-cached-count="comment.likeable.likesCachedCount"
          :likeable-id="comment.likeable.id"
          :is-liked-by-user="comment.likeable.isLikedByUser"
        />
      </div>
    </div>
    <ViewportIntersectionObserver
      v-show="!isLoading && comments.doesCursorHaveMore()"
      @intersects="fetchNextPage"
      :restore-on-change-of="comments.items.length"
    />

    <div v-if="isLoading" class="commentable-comments__loading">
      <LoadingSpinner :delay-ms="300" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, watch } from "vue";
import { formatDateLongAgo } from "@/util/date";
import CommentItem from "@/components/Comments/CommentItem.vue";
import CommentPostForm from "@/components/Comments/CommentPostForm.vue";
import LoadingSpinner from "@/components/Util/LoadingSpinner.vue";
import ViewportIntersectionObserver from "@/components/Util/ViewportIntersectionObserver.vue";
import { useCommentable } from "@/composable/comments/useCommentable";
import type Comment from "@/api/Resources/Types/Comment";

const props = defineProps<{
  commentableId: number;
}>();

// prettier-ignore
const { 
  comments, fetchFirstPage, fetchNextPage,
  onNewUserPostedComment, loadedCommentable, isLoading,
  incrementRootChildCommentsCachedCount,
} = useCommentable();

const onCommentPosted = (comment: Comment) => {
  onNewUserPostedComment(comment);
};

const onReplyPosted = (reply: Comment) => {
  incrementRootChildCommentsCachedCount(reply.rootCommentId);
};

const ensureCommentsLoaded = () => {
  if (loadedCommentable.value !== props.commentableId) {
    if (props.commentableId) fetchFirstPage(props.commentableId);
  }
};
const commentsHaveToBeLoaded = computed(() => props.commentableId);
watch(
  commentsHaveToBeLoaded,
  () => {
    if (commentsHaveToBeLoaded.value) ensureCommentsLoaded();
  },
  { immediate: true }
);
</script>

<style scoped>
.commentable-comments {
  display: flex;
  flex-direction: column;
  align-items: stretch;
}

.commentable-comments__loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-bottom: 50vh;
}

.commentable-comments__item-wrapper {
  border-top: solid 1px #f5f5f5;
}
</style>
