<template>
  <div class="page user-page">
    <div class="user-page__header-navbar" ref="refHeaderNavbar">
      <MainHeaderNavbar />
    </div>

    <div class="user-page__wrapper">
      <div class="user-page__content-wrapper">
        <div class="user-page__content limited-to-content-width">
          <div class="user-page__content__sidebar">
            <div class="user-page__content__sidebar__blocks sticky-to-top-element" ref="refStickySidebar">
              <div class="user-page__content__sidebar__block">
                <div class="user-page__content__sidebar__username text-color-accent-3">
                  <SkeletonLine v-if="isLoading" :height="25" />
                  <div v-if="!isLoading && userProfileMainInfo">
                    {{ userProfileMainInfo && userProfileMainInfo.name }}
                  </div>
                </div>

                <div class="user-page__content__sidebar__image--wrapper padding-t-15 padding-x-75">
                  <div class="user-page__content__sidebar__image-wrapper">
                    <SkeletonSquare v-if="isLoading" class="user-page__content__sidebar__image round-angles-small" />
                    <div
                      v-if="!isLoading && userProfileMainInfo"
                      :style="`background: center / contain no-repeat url(${userProfileMainInfo.avatar.medium})`"
                      class="user-page__content__sidebar__image round-angles-small"
                    ></div>
                  </div>
                </div>

                <div class="user-page__content__sidebar__statistics padding-x-50 padding-t-15 padding-b-10">
                  <!-- <StatisticsLikesCount :likes="(userProfileMainInfo && userProfileMainInfo.statistics.likes) || 0" />
                  <StatisticsViewsCount :views="(userProfileMainInfo && userProfileMainInfo.statistics.views) || 0" />
                  <StatisticsCommentsCount :comments="(userProfileMainInfo && userProfileMainInfo.statistics.comments) || 0" /> -->
                  <!-- <StatisticsLikesCount :likes="user.statistics.stars" /> -->
                </div>
              </div>
              <div class="user-page__content__sidebar__block user-page__content__sidebar__block__filler">
                <div v-if="!isLoading" class="user-page__content__sidebar__navigation">
                  <router-link
                    :to="{ name: 'user.lists.', params: { user: route.params.user } }"
                    class="user-page__content__sidebar__navigation__page-link navbar-page-link"
                  >
                    Lists
                  </router-link>
                  <!-- <router-link
                    :to="{ name: 'user.notifications.', params: { user: route.params.user } }"
                    class="user-page__content__sidebar__navigation__page-link navbar-page-link"
                  >
                    Notifications
                  </router-link> -->
                  <router-link
                    :to="{ name: 'user.comments.', params: { user: route.params.user } }"
                    class="user-page__content__sidebar__navigation__page-link navbar-page-link"
                  >
                    Comments
                  </router-link>
                </div>
              </div>
            </div>
          </div>
          <div class="user-page__content__aside">
            <SkeletonLine class="padding-y-15 padding-x-25" v-if="isLoading" :height="20" />
            <div v-if="!isLoading && userProfileMainInfo">
              <router-view
                :key="route.params.user"
                :user="userProfileMainInfo.id"
                :comic-user-lists="userProfileMainInfo.comicUserLists"
              />
            </div>
            <div v-if="route.name === 'user.'" class="small-bold-title padding-t-10 text-center">
              Select some page in the sidebar
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, nextTick, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useUserProfileMainInfo } from "@/composable/user/useUserProfileMainInfo";
import MainHeaderNavbar from "@/components/MainHeaderNavbar.vue";
import SkeletonSquare from "@/components/Skeleton/SkeletonSquare.vue";
import SkeletonLine from "@/components/Skeleton/SkeletonLine.vue";
// import StatisticsLikesCount from "@/components/Statistics/StatisticsLikesCount.vue";
// import StatisticsCommentsCount from "@/components/Statistics/StatisticsCommentsCount.vue";
// import StatisticsViewsCount from "@/components/Statistics/StatisticsViewsCount.vue";

const route = useRoute();
const router = useRouter();
const { fetchUserProfileMainInfo, isLoading, userProfileMainInfo } = useUserProfileMainInfo();

const goToDefaultChildRoute = () => {
  router.replace({
    name: "user.lists.",
    params: {
      user: route.params.user,
    },
  });
};

// Fetch other user on route change and component setup
watch(
  () => route.path,
  () => {
    if (route.name === "user.") {
      goToDefaultChildRoute();
    }

    if (
      route.name?.toString().startsWith("user.") &&
      parseInt((route.params.user as string | undefined) || "0") !==
        (userProfileMainInfo.value && userProfileMainInfo.value.id)
    ) {
      fetchUserProfileMainInfo(parseInt(route.params.user as string));
    }
  },
  { immediate: true }
);

const refHeaderNavbar = ref<HTMLElement | null>(null);
const refStickySidebar = ref<HTMLElement | null>(null);

// <layout stuff>
const calculateNavbarHeightDependants = () => {
  if (refStickySidebar.value && refHeaderNavbar.value) {
    refStickySidebar.value.style.top = `${refHeaderNavbar.value.offsetHeight}px`;
    refStickySidebar.value.style.height = `calc(100vh - ${refHeaderNavbar.value.offsetHeight}px)`;
  }
};

onMounted(() => nextTick(() => calculateNavbarHeightDependants()));
// </layout stuff>
</script>

<style scoped>
.user-page__content__sidebar__navigation {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.user-page__content__sidebar__navigation__page-link {
  padding: 5px 0;
  font-size: 14px;
  line-height: 20px;
}

.user-page__heading-background {
  position: absolute;
  z-index: -1;
  overflow: hidden;
  width: 100%;
}

.user-page__content {
  display: flex;
  background: #f5f5f5;
}

.user-page__content__aside {
  flex: 1;
  background: white;
}

.user-page__content__aside__block:last-child,
.user-page__content__sidebar__block:last-child {
  margin-bottom: 0px;
}

.user-page__content__sidebar__block,
.user-page__content__aside__block {
  padding: 15px 25px;
  margin-bottom: 1px;
  background: white;
}

.user-page__content__sidebar {
  width: 320px;
  margin-right: 1px;
  background: #f5f5f5;
  box-sizing: border-box;
}

.user-page__content__sidebar__blocks {
  display: flex;
  flex-direction: column;
}

.user-page__content__sidebar__block__filler {
  flex: 1;
}

.user-page__content__sidebar__statistics {
  display: flex;
  justify-content: space-between;
}

.user-page__content__sidebar__username {
  text-align: center;
  font-weight: 500;
  font-size: 22px;
  line-height: 27px;
}

.user-page__content__sidebar__image {
  position: absolute;
  top: 0;
  width: 100%;
  height: 100%;
}

.user-page__content__sidebar__image-wrapper:before {
  content: " ";
  display: block;
  width: 100%;
  padding-top: 100%;
}

.user-page__content__sidebar__image-wrapper {
  position: relative;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
}
</style>
