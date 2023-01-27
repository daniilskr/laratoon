<template>
  <BaseUserNestedView class="user-comments">
    <template v-slot:navbar>
      <h1 class="navbar-page-title">Comments</h1>
    </template>
    <template v-slot:content>
      <div class="user-comments__content padding-x-75">
        <div v-if="!isLoading && userComments.items.length === 0" class="small-bold-title text-center padding-t-25">
          No comments so far...
        </div>
        <div class="padding-t-25">
          <div class="padding-x-10 padding-b-10 padding-t-15">
            <div
              v-for="userComment in userComments.items"
              :key="userComment.id"
              class="user-comments__item-wrapper padding-t-25 padding-t-0first"
            >
              <div class="padding-b-15">
                <UserComment
                  :id="userComment.id"
                  :commentable-owner-label="userComment.commentable.owner.label"
                  :published-at="userComment.publishedAt"
                  :text="userComment.text"
                  :likes="userComment.likeable.likesCachedCount ?? 0"
                  :replies="userComment.rootChildCommentsCachedCount ?? 0"
                />
              </div>
            </div>
            <ViewportIntersectionObserver
              v-show="!isLoading && userComments.doesCursorHaveMore()"
              @intersects="fetchNextPage"
              :restore-on-change-of="userComments.items.length"
            />
          </div>
        </div>
        <div v-if="isLoading" class="user-comments__loading">
          <LoadingSpinner :delay-ms="300" />
        </div>
      </div>
    </template>
  </BaseUserNestedView>
</template>

<script setup lang="ts">
import { computed, watch } from "vue";
import { useRoute } from "vue-router";
import UserComment from "@/components/User/UserComment.vue";
import BaseUserNestedView from "@/views/User/BaseUserNestedView.vue";
import { useUserComments } from "@/composable/user/useUserComments";
import ViewportIntersectionObserver from "@/components/Util/ViewportIntersectionObserver.vue";
import LoadingSpinner from "@/components/Util/LoadingSpinner.vue";

const route = useRoute();

// prettier-ignore
const {
  userComments, fetchFirstPage,
  fetchNextPage, loadedUserId, isLoading,
} = useUserComments();

// <Trigger user comments fetch when needed>
const userToLoad = computed(() => parseInt(route.params.user as string) || null);

const ensureUserCommentsLoaded = () => {
  if (loadedUserId.value !== userToLoad.value) {
    if (userToLoad.value) fetchFirstPage(userToLoad.value);
  }
};

watch(
  userToLoad,
  (now, before) => {
    if (now !== null && now > 0 && now !== before) {
      ensureUserCommentsLoaded();
    }
  },
  { immediate: true }
);
// </Trigger user comments fetch when needed>
</script>

<style scoped>
.user-comments {
  position: relative;
}

.user-comments__loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-bottom: 20vh;
}

.user-comments__item-wrapper {
  border-bottom: 1px solid #f5f5f5;
}

.user-comments__item-wrapper:last-child {
  border-bottom: none;
}
</style>
