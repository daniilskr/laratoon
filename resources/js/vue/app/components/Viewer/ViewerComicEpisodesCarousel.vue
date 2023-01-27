<template>
  <div class="viewer-comic-episodes-carousel">
    <div
      @click="canGoToPreviousPage && currentPage--"
      :class="[
        'viewer-comic-episodes-carousel__btn',
        { 'viewer-comic-episodes-carousel__btn--disabled': !canGoToPreviousPage },
      ]"
    >
      <IconTriangle direction="left" fill-color="#b1b1b1" width="24" height="24" />
    </div>
    <div class="viewer-comic-episodes-carousel__content-frame">
      <div
        v-show="episodes.length > 0"
        :style="`transform: translateX(-${100 * currentPage}%);`"
        class="viewer-comic-episodes-carousel__content-frame__tape"
      >
        <div
          v-for="episode in episodes"
          :key="episode.number"
          :class="[
            'viewer-comic-episodes-carousel__episode-item padding-x-10',
            { 'viewer-comic-episodes-carousel__episode-item--active': episodeId === episode.id },
          ]"
        >
          <router-link :to="{ name: 'viewer.', params: { comic: loadedComic, episode: episode.number } }">
            <div class="viewer-comic-episodes-carousel__episode-item__image">
              <div
                :style="`background: center / contain no-repeat url(${episode.poster.medium}`"
                class="viewer-comic-episodes-carousel__episode-item__image__img"
              ></div>
            </div>
            <div class="viewer-comic-episodes-carousel__episode-item__number">#{{ episode.number }}</div>
          </router-link>
        </div>
      </div>
    </div>
    <div
      @click="canGoToNextPage && currentPage++"
      :class="[
        'viewer-comic-episodes-carousel__btn',
        { 'viewer-comic-episodes-carousel__btn--disabled': !canGoToNextPage },
      ]"
    >
      <IconTriangle direction="right" fill-color="#b1b1b1" width="24" height="24" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import IconTriangle from "@/components/Icons/IconTriangle.vue";
import type Episode from "@/api/Resources/Types/Episode";
import { BACKEND_URL } from "@/config";
import axios from "axios";
import { computed } from "@vue/reactivity";
import { parseEpisode } from "@/api/Resources/Types/Episode";

const props = defineProps<{
  comicSlug: string;
  episodeId: null | number;
}>();

const episodes = ref<Episode[]>([]);

const EPISODES_PER_PAGE = 8;
const currentPage = ref(0);
const maxPages = computed(() => Math.ceil(episodes.value.length / EPISODES_PER_PAGE));

const canGoToPreviousPage = computed(() => currentPage.value > 0);
const canGoToNextPage = computed(() => currentPage.value + 1 < maxPages.value);

const goToCurrentEpisodePage = () => {
  const curEpisodePosition = episodes.value.findIndex((el) => el.id === props.episodeId);
  currentPage.value = Math.floor(curEpisodePosition / EPISODES_PER_PAGE);
};

const loadedComic = ref<null | string>(null);

const fetchEpisodes = (comicSlug: string) => {
  axios.get(`${BACKEND_URL}/api/comic-by-slug/${comicSlug}/episodes`).then((response) => {
    if (response.status == 200) {
      loadedComic.value = comicSlug;

      /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
      episodes.value = response.data.data.map((episode: any) => parseEpisode(episode));
    }

    goToCurrentEpisodePage();
  });
};

// Fetch other comic episodes on route change and component setup
watch(
  () => props.comicSlug,
  () => {
    if (props.comicSlug && props.comicSlug !== loadedComic.value) {
      fetchEpisodes(props.comicSlug);
    }
  },
  { immediate: true }
);
</script>

<style scoped>
.viewer-comic-episodes-carousel {
  display: flex;
  width: 100%;
}

.viewer-comic-episodes-carousel__content-frame {
  overflow: hidden;
}

.viewer-comic-episodes-carousel__content-frame__tape {
  display: flex;
  transition: transform 0.5s ease-in-out;
}

.viewer-comic-episodes-carousel__episode-item {
  min-width: v-bind("`${(100 / EPISODES_PER_PAGE)}%`");
  display: flex;
  flex-direction: column;
}

.viewer-comic-episodes-carousel__episode-item__image {
  position: relative;
}

.viewer-comic-episodes-carousel__episode-item__image:before {
  content: " ";
  display: block;
  width: 100%;
  padding-top: 100%;
}

.viewer-comic-episodes-carousel__episode-item__image__img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.viewer-comic-episodes-carousel__episode-item__number {
  background: white;
  color: black;
  border-top: 4px solid white;
  border-bottom: 4px solid white;
}

.viewer-comic-episodes-carousel__episode-item--active .viewer-comic-episodes-carousel__episode-item__number {
  border-top: 4px solid #2dbcdc;
}

.viewer-comic-episodes-carousel__episode-item--active .viewer-comic-episodes-carousel__episode-item__image {
  border: 5px solid white;
}

.viewer-comic-episodes-carousel__content-frame {
  flex: 1;
}

.viewer-comic-episodes-carousel__btn {
  flex: 0;
  padding: 0 10px;
  display: flex;
  align-items: center;
  cursor: pointer;
}

.viewer-comic-episodes-carousel__btn--disabled {
  opacity: 0;
  cursor: initial;
}
</style>
