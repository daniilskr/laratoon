<template>
  <BaseUserNestedView class="user-list">
    <template v-slot:navbar>
      <router-link
        v-for="comicUserList in sortedComicUserLists"
        :key="comicUserList.id"
        :to="{ name: 'user.lists.', params: { user: route.params.user, list: slugify(comicUserList.name) } }"
        class="user-list__navbar__list-link navbar-page-link"
      >
        <HexColoredCircle :color="comicUserList.color" />
        <div class="user-list__navbar__list-link__text">{{ comicUserList.name }}</div>
      </router-link>
    </template>
    <template v-slot:content>
      <div class="user-list__content padding-x-75 padding-t-5">
        <div class="user-list__comic-cards-grid-square-cards padding-xy-10">
          <ComicCardsGridSmallTallCards :cards="cardsToRender" />
          <div
            v-if="!isLoading && listToLoad && entries.items.length === 0"
            class="small-bold-title padding-t-10 text-center"
          >
            No comics in this list...
          </div>
          <ViewportIntersectionObserver
            v-show="!isLoading && entries.doesCursorHaveMore()"
            @intersects="fetchNextPage"
            :restore-on-change-of="entries.items.length"
          />
          <div v-if="isLoading" class="user-list__loading">
            <LoadingSpinner :delay-ms="300" />
          </div>
        </div>
      </div>
    </template>
  </BaseUserNestedView>
</template>

<script setup lang="ts">
import { computed, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import ComicCardsGridSmallTallCards from "@/components/Comic/ComicCardsGridSmallTallCards.vue";
import HexColoredCircle from "@/components/Util/HexColoredCircle.vue";
import BaseUserNestedView from "@/views/User/BaseUserNestedView.vue";
import ViewportIntersectionObserver from "@/components/Util/ViewportIntersectionObserver.vue";
import LoadingSpinner from "@/components/Util/LoadingSpinner.vue";
import { useComicUserListEntries } from "@/composable/user/useComicUserListEntries";
import type ComicUserList from "@/api/Resources/Types/ComicUserList";
import { slugify } from "@/util/strings";

const route = useRoute();
const router = useRouter();

const props = defineProps<{
  user: number;
  comicUserLists: Array<ComicUserList>;
}>();

const sortedComicUserLists = computed(() => [...props.comicUserLists].sort((a, b) => a.id - b.id));

// prettier-ignore
const {
  entries, fetchFirstPage, fetchNextPage,
  loadedComicUserListId, isLoading, reset,
} = useComicUserListEntries();

const cardsToRender = computed(() =>
  entries.items.map((entry) => ({
    id: entry.id,
    image: entry.comic.comicPoster,
    slug: entry.comic.slug,
    author: entry.comic.author.name,
    title: entry.comic.title,
    main: "#" + (entry.comic.cachedLatestViewedEpisode ? entry.comic.cachedLatestViewedEpisode.number : 1),
    additional: `${entry.comic.episodesLeft} left`,
    linkTo: { name: "comic.", params: { comic: entry.comic.slug } },
  }))
);

// <Trigger entires fetch when needed>
const listToLoad = computed(
  () => sortedComicUserLists.value.find((i) => slugify(i.name) === (route.params.list as string)) || null
);

const ensureEntriesLoaded = () => {
  if (listToLoad.value && loadedComicUserListId.value !== listToLoad.value.id) {
    if (listToLoad.value) fetchFirstPage(listToLoad.value.id);
  }
};

watch(
  listToLoad,
  (now) => {
    if (now === null) {
      reset();
    } else {
      ensureEntriesLoaded();
    }
  },
  { immediate: true }
);
// </Trigger entires fetch when needed>

// <Go to default list>
const defaultListToGo = computed(() =>
  sortedComicUserLists.value.length > 0 ? sortedComicUserLists.value[0].name : null
);

const goToDefaultList = () => {
  router.replace({
    name: "user.lists.",
    params: {
      user: route.params.user,
      list: defaultListToGo.value,
    },
  });
};

watch(
  () => route.path,
  () => {
    if (
      route.name === "user.lists." &&
      parseInt((route.params.list as string) || "0") === 0 &&
      defaultListToGo.value !== null
    ) {
      goToDefaultList();
    }
  },
  { immediate: true }
);
// </Go to default list>
</script>

<style scoped>
.user-list {
  position: relative;
}

.user-list__loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-bottom: 20vh;
  padding-top: 25px;
}
.user-list__navbar__list-link__text {
  padding-left: 7px;
}

.user-list__navbar__list-link {
  margin-right: 30px;
  display: flex;
  align-items: baseline;
}
</style>
