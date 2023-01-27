<template>
  <div class="page viewer-page">
    <ViewerHeaderNavbar
      :comic-title="episodeMainInfo && episodeMainInfo.title"
      :comic-slug="($route.params.comic as string)"
      :episode-number="parseInt($route.params.episode as string)"
    />

    <div class="viewer-page__content limited-to-viewer-width">
      <div class="viewer-page__content__episode-render padding-y-15">
        <ViewerComicEpisodeRender v-if="episodeMainInfo" :pages="episodeMainInfo.pages" />
      </div>
      <ViewportIntersectionObserver
        v-if="episodeMainInfo"
        :key="episodeMainInfo.id"
        @intersects="onIntersection"
        style="transform: translateY(-100vh)"
      />
      <div class="viewer-page__content__episode-carousel padding-y-15">
        <ViewerComicEpisodesCarousel
          v-if="route.params.comic && showCarousel"
          :comic-slug="(route.params.comic as string)"
          :episode-id="episodeMainInfo && episodeMainInfo.id"
        />
      </div>
      <div class="viewer-page__content__comments-wrapper padding-y-15">
        <div class="viewer-page__content__comments padding-x-15 padding-t-15">
          <h1 class="thick-uppercase-shrinked-title text-left text-color-accent-5 padding-b-5">Comments</h1>
          <Comments
            v-if="episodeMainInfo && showComments"
            :key="episodeMainInfo.commentable.id"
            :commentable-id="episodeMainInfo.commentable.id"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import ViewerHeaderNavbar from "@/components/Viewer/ViewerHeaderNavbar.vue";
import ViewerComicEpisodesCarousel from "@/components/Viewer/ViewerComicEpisodesCarousel.vue";
import ViewerComicEpisodeRender from "@/components/Viewer/ViewerComicEpisodeRender.vue";
import ViewportIntersectionObserver from "@/components/Util/ViewportIntersectionObserver.vue";
import Comments from "@/components/Comments/Comments.vue";
import { ref, watch } from "vue";
import type EpisodeMainInfo from "@/api/Resources/Types/EpisodeMainInfo";
import axios from "axios";
import { useRoute, onBeforeRouteUpdate } from "vue-router";
import { BACKEND_URL } from "@/config";
import { parseEpisodeMainInfo } from "@/api/Resources/Types/EpisodeMainInfo";

const route = useRoute();

// <COMMENTS-AND-CAROUSEL-LAZY-LOAD>
const showCarousel = ref(false);
const showComments = ref(false);

onBeforeRouteUpdate(() => {
  // hide comments since we need to lazy load them
  showComments.value = false;
});

const onIntersection = () => {
  showComments.value = true;

  if (!showCarousel.value) {
    showCarousel.value = true;
  }
};
// </ COMMENTS-AND-CAROUSEL-LAZY-LOAD>

const episodeMainInfo = ref<null | EpisodeMainInfo>(null);

const fetchEpisodeMainInfo = (comicSlug: string, episodeNumber: number) => {
  axios.get(`${BACKEND_URL}/api/comic-by-slug/${comicSlug}/episodes/${episodeNumber}/main-info`).then((response) => {
    if (response.status == 200) {
      episodeMainInfo.value = parseEpisodeMainInfo(response.data.data);
    }
  });
};

// Fetch other comic on route change and component setup
watch(
  () => route.params.comic && route.params.episode,
  () => {
    if (route.name === "viewer.")
      fetchEpisodeMainInfo(route.params.comic as string, parseInt(route.params.episode as string));
  },
  { immediate: true }
);
</script>

<style scoped>
.viewer-page__content__episode-render {
  min-height: 200vh;
}

.viewer-page__content__comments {
  background: white;
}
</style>
