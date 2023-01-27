<template>
  <div :class="['page', 'comic-page', 'comic-page--comic-data-' + (showSkeleton ? 'loading' : 'loaded')]">
    <div class="comic-page__header-navbar" ref="refHeaderNavbar">
      <MainHeaderNavbar />
    </div>

    <div
      :class="[
        'comic-page__wrapper',
        { 'navbar-height-dependants-calculated': layoutState.navbarHeightDependantsCalculated },
      ]"
    >
      <div class="comic-page__heading-background heading-section-height">
        <div
          v-if="!isLoading && comicMainInfo"
          :style="`background: url(${comicMainInfo.comicHeaderBackground.medium}) no-repeat center`"
          class="comic-page__heading-background__img"
        />
      </div>

      <div class="comic-page__content-wrapper">
        <div class="comic-page__content limited-to-content-width" ref="refComicPageContent">
          <div class="comic-page__content__sidebar">
            <div class="comic-page__content__sidebar__blocks sticky-to-top-element" ref="refStickySidebar">
              <div class="comic-page__content__sidebar__block">
                <div v-if="!isLoading && comicMainInfo">
                  <div class="comic-page__content__sidebar__title">{{ comicMainInfo.title }}</div>
                  <div class="comic-page__content__sidebar__author text-color-space-5">
                    {{ comicMainInfo.author.fullName }}
                  </div>
                </div>
                <SkeletonLine :height="40" v-show="showSkeleton" />
              </div>

              <div class="comic-page__content__sidebar__collapsable-block">
                <CroppableBlock :crop="sidebarCroppableBlockCrop">
                  <div class="comic-page__content__sidebar__collapsable-block__content">
                    <div class="comic-page__content__sidebar__block">
                      <div class="comic-page__content__sidebar__image padding-x-25">
                        <div v-if="!isLoading && comicMainInfo">
                          <div class="comic-page__content__sidebar__image__wrapper">
                            <img
                              class="comic-page__content__sidebar__image__wrapper__img round-angles-small"
                              :src="comicMainInfo.comicPoster.medium"
                            />
                          </div>
                        </div>
                        <SkeletonSquare v-show="showSkeleton" />
                      </div>
                    </div>
                    <div class="comic-page__content__sidebar__block">
                      <div class="padding-x-50">
                        <div v-if="!isLoading && comicMainInfo" class="comic-page__content__sidebar__statistics">
                          <StatisticsLikesCount :likes="comicMainInfo.statistics.likes.total" />
                          <StatisticsViewsCount :views="comicMainInfo.statistics.views.total" />
                          <StatisticsCommentsCount :comments="comicMainInfo.statistics.comments.total" />
                        </div>
                        <SkeletonLine :height="18" v-show="showSkeleton" />
                      </div>
                    </div>
                  </div>
                </CroppableBlock>
              </div>
              <div class="comic-page__content__sidebar__block">
                <div class="comic-page__content__sidebar__actions padding-x-25">
                  <div v-if="!isLoading && comicMainInfo">
                    <div class="comic-page__content__sidebar__actions__item">
                      <router-link :to="`/read/${comicMainInfo.slug}/${latestViewedEpisodeNumber || 1}`">
                        <button class="btn btn-outline-stylish w-100">
                          {{
                            latestViewedEpisodeNumber === 1
                              ? "Read first episode"
                              : `Continue #${latestViewedEpisodeNumber}`
                          }}
                        </button>
                      </router-link>
                    </div>
                    <div class="comic-page__content__sidebar__actions__item">
                      <ComicUserListSelect
                        :key="comicMainInfo.id"
                        :comic-id="comicMainInfo.id"
                        :initial-comic-user-list-slug="comicMainInfo.comicUserListSlug"
                      />
                    </div>
                  </div>
                  <SkeletonLine :height="30" v-show="showSkeleton" class="padding-y-5" />
                </div>
              </div>
              <div class="comic-page__content__sidebar__block comic-page__content__sidebar__block__filler"></div>
            </div>
          </div>
          <div class="comic-page__content__aside">
            <div class="comic-page__content__aside__description comic-page__content__aside__block text-left">
              <div v-if="!isLoading && comicMainInfo" class="text-color-accent-1">
                {{ comicMainInfo.description }}
              </div>
              <SkeletonLine :height="50" v-show="showSkeleton" />
            </div>
            <div class="comic-page__content__aside__block">
              <div v-if="!isLoading && comicMainInfo">
                <h1 class="thick-uppercase-shrinked-title text-left text-color-accent-5 padding-b-25">Episodes</h1>
                <ComicEpisodes :comic-slug="comicMainInfo.slug" :episodes="comicEpisodesPreview" />
              </div>
              <div v-show="showSkeleton">
                <SkeletonLineWithSquare v-for="n in 5" :key="n" :square-width="80" class="padding-b-5" />
              </div>
            </div>
            <!-- <div class="comic-page__content__aside__block">
              <div v-if="!isLoading && comicMainInfo">
                <h1 class="thick-uppercase-shrinked-title text-left text-color-accent-5 padding-b-25">Bookmarks</h1>
                <ComicBookmarks />
              </div>
              <div v-show="showSkeleton">
                <SkeletonLineWithSquare v-for="n in 5" :key="n" :square-width="40" class="padding-b-5" />
              </div>
            </div> -->
            <div class="comic-page__content__aside__block">
              <div v-if="!isLoading && comicMainInfo">
                <h1 class="thick-uppercase-shrinked-title text-left text-color-accent-5 padding-b-25">Tags</h1>
                <ComicTags :tags="comicTagsPreview" />
              </div>
              <div v-show="showSkeleton">
                <SkeletonLine :height="25" />
              </div>
            </div>
            <div class="comic-page__content__aside__block">
              <div v-if="!isLoading && comicMainInfo">
                <h1 class="thick-uppercase-shrinked-title text-left text-color-accent-5 padding-b-25">Characters</h1>
                <ComicCharacters :charactersRoles="comicCharactersPreview" />
              </div>
              <div v-show="showSkeleton">
                <SkeletonLine :height="50" />
              </div>
            </div>
            <div class="comic-page__content__aside__block">
              <div v-if="!isLoading && comicMainInfo">
                <ViewportIntersectionObserver :key="comicMainInfo.id" @intersects="showComments = true" />
                <h1 class="thick-uppercase-shrinked-title text-left text-color-accent-5 padding-b-25">
                  Other comics of the author
                </h1>
                <ComicCardsSectionSmallWideCards :comic-cards="comicMainInfo.otherComicsByAuthor" />
              </div>
              <div v-show="showSkeleton">
                <SkeletonLine v-for="n in 3" :key="n" :height="40" class="padding-b-15" />
              </div>
            </div>
            <div class="comic-page__content__aside__block">
              <div v-if="!isLoading && comicMainInfo">
                <h1 class="thick-uppercase-shrinked-title text-left text-color-accent-5 padding-b-25">Comments</h1>
                <Comments
                  v-if="comicMainInfo && showComments"
                  :key="comicMainInfo.commentable.id"
                  :commentable-id="comicMainInfo.commentable.id"
                />
              </div>
              <div v-show="showSkeleton">
                <SkeletonLineWithSquare v-for="n in 7" :key="n" :square-width="40" class="padding-b-5" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import _ from "lodash";
import { onMounted, onUnmounted, reactive, nextTick, ref, computed, watch } from "vue";
import { useRoute, onBeforeRouteUpdate } from "vue-router";
import SkeletonLine from "@/components/Skeleton/SkeletonLine.vue";
import SkeletonSquare from "@/components/Skeleton/SkeletonSquare.vue";
import SkeletonLineWithSquare from "@/components/Skeleton/SkeletonLineWithSquare.vue";
import MainHeaderNavbar from "@/components/MainHeaderNavbar.vue";
import ComicCardsSectionSmallWideCards from "@/components/Comic/ComicCardsSectionSmallWideCards.vue";
import StatisticsLikesCount from "@/components/Statistics/StatisticsLikesCount.vue";
import StatisticsCommentsCount from "@/components/Statistics/StatisticsCommentsCount.vue";
import StatisticsViewsCount from "@/components/Statistics/StatisticsViewsCount.vue";
import ComicCharacters from "@/components/Comic/ComicCharacters.vue";
import ComicEpisodes from "@/components/Comic/ComicEpisodes.vue";
import ComicTags from "@/components/Comic/ComicTags.vue";
import Comments from "@/components/Comments/Comments.vue";
import ComicUserListSelect from "@/components/Comic/ComicUserListSelect.vue";
import CroppableBlock from "@/components/Util/CroppableBlock.vue";
import ViewportIntersectionObserver from "@/components/Util/ViewportIntersectionObserver.vue";
import { useComicMainInfo } from "@/composable/comic/useComicMainInfo";

const refHeaderNavbar = ref<HTMLElement | null>(null);
const refStickySidebar = ref<HTMLElement | null>(null);
const refComicPageContent = ref<HTMLElement | null>(null);

const route = useRoute();

// <COMMENTS-LAZY-LOAD>
const showComments = ref(false);
onBeforeRouteUpdate(() => {
  // hide comments since we need to lazy load them
  showComments.value = false;
});
// </ COMMENTS-LAZY-LOAD>

const { comicMainInfo, fetchComicMainInfo, isLoading } = useComicMainInfo();

const latestViewedEpisodeNumber = computed(() =>
  comicMainInfo.value && comicMainInfo.value.cachedLatestViewedEpisode
    ? comicMainInfo.value.cachedLatestViewedEpisode.number
    : 1
);

const comicEpisodesPreview = computed(() =>
  _.takeRight((comicMainInfo.value && comicMainInfo.value.episodes) || [], 5)
);

const comicCharactersPreview = computed(() =>
  _.takeRight((comicMainInfo.value && comicMainInfo.value.mainCharacters) || [], 9)
);

const comicTagsPreview = computed(() => (comicMainInfo.value && comicMainInfo.value.tags) || []);

// isNullInstance означает, что компонент только инициализировался,
// и никакой информации еще не было загружено.
// При переключении на другой комикс на протяжении всей загрузки
// будет оставаться инстанс текущего комикса
const isNullInstance = computed(() => _.isNull(comicMainInfo.value && comicMainInfo.value.id));
const showSkeleton = computed(() => isLoading.value || isNullInstance.value);

// Fetch other comic on route change and component setup
watch(
  () => route.params.comic,
  () => {
    if (route.name === "comic.") fetchComicMainInfo(route.params.comic as string);
  },
  { immediate: true }
);

// <LAYOUT-STUFF>
const layoutState = reactive({
  sidebar_offsetTop: 0,
  comicPageContent_offsetTop: 0,
  navbarHeightDependantsCalculated: false,
});

onMounted(() => nextTick(() => calculateNavbarHeightDependants()));

const calculateNavbarHeightDependants = () => {
  // mostly is just an attempt to silence typescript "Object is possibly null"
  if (refStickySidebar.value && refHeaderNavbar.value && refStickySidebar.value) {
    refStickySidebar.value.style.top = refHeaderNavbar.value.offsetHeight + "px";
    refStickySidebar.value.style.height = "calc(100vh - " + refHeaderNavbar.value.offsetHeight + "px)";
    syncSidebarOffsetTopData();
    calculateCollapsableBlockCrop();
    layoutState.navbarHeightDependantsCalculated = true;
  }
};

const syncSidebarOffsetTopData = () => {
  if (refComicPageContent.value && refStickySidebar.value) {
    layoutState.sidebar_offsetTop = refStickySidebar.value.offsetTop;
    layoutState.comicPageContent_offsetTop = refComicPageContent.value.offsetTop;
  }
};

const sidebarCroppableBlockCrop = ref(0);

const calculateCollapsableBlockCrop = () => {
  sidebarCroppableBlockCrop.value = Math.max(layoutState.sidebar_offsetTop - layoutState.comicPageContent_offsetTop, 0);
};

const handleScroll = () => {
  syncSidebarOffsetTopData();
  // remove this line on mobile/tablets
  calculateCollapsableBlockCrop();
};

window.addEventListener("scroll", handleScroll);
onUnmounted(() => window.removeEventListener("scroll", handleScroll));
// </ LAYOUT-STUFF>
</script>

<style scoped>
.comic-page__heading-background {
  background: #e9e9e9;
  position: absolute;
  z-index: -1;
  overflow: hidden;
  width: 100%;
}

.comic-page__heading-background__img {
  width: 100%;
  height: 100%;
  animation: appear-animation 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.comic-page--comic-data-loading .comic-page__heading-background__img {
  filter: blur(10px);
}

.comic-page--comic-data-loaded .comic-page__heading-background__img {
  filter: blur(0);
  transition: filter 0.1s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes appear-animation {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.comic-page__content-wrapper {
  padding-top: 180px;
}

.comic-page__content {
  display: flex;
  background: #f5f5f5;
}

.comic-page__content__aside {
  flex: 1;
  min-width: 0;
}

.comic-page__content__aside > .comic-page__content__aside__block:last-child,
.comic-page__content__sidebar > .comic-page__content__sidebar__block:last-child {
  margin-bottom: 0px;
}

.comic-page__content__sidebar__block,
.comic-page__content__aside__block {
  padding: 15px 25px;
  margin-bottom: 1px;
  background: white;
}

.comic-page__content > div:first-child {
  margin-right: 1px;
}

.comic-page__content__sidebar {
  width: 320px;
  background: #f5f5f5;
  box-sizing: border-box;
}

.comic-page__content__sidebar__collapsable-block {
  overflow: hidden;
}

.comic-page__content__sidebar__blocks {
  display: flex;
  flex-direction: column;
}

.comic-page__content__sidebar__block__filler {
  flex: 1;
}

.comic-page__content__sidebar__statistics {
  display: flex;
  justify-content: space-between;
}

.comic-page__content__aside__description,
.comic-page__content__sidebar__description {
  font-size: 16px;
}

.comic-page__content__sidebar__title {
  text-align: left;
  font-weight: 500;
  font-size: 25px;
  line-height: 24px;
}

.comic-page__content__sidebar__author {
  text-align: left;
  font-weight: 500;
  font-size: 15px;
}

.comic-page__content__sidebar__image {
  position: relative;
}

.comic-page__content__sidebar__image__wrapper {
  position: relative;
  display: block;
  width: 100%;
  height: 100%;
}

.comic-page__content__sidebar__image__wrapper::before {
  content: " ";
  display: block;
  width: 100%;
  padding-top: 100%;
}

.comic-page__content__sidebar__image__wrapper__img {
  top: 0;
  left: 0;
  position: absolute;
  display: block;
  width: 100%;
  height: 100%;
}

.comic-page__content__sidebar__actions__item:not(:last-child) {
  padding-bottom: 10px;
}
</style>
