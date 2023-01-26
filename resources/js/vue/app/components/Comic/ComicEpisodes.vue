<template>
  <div class="comic-episodes">
    <div v-for="episode in episodes" :key="episode.number" class="comic-episodes__item-wrapper">
      <router-link :to="{ name: 'viewer.', params: { comic: comicSlug, episode: episode.number } }">
        <ComicEpisodeItem
          :title="episode.title"
          :date="episode.publishedAt"
          :number="episode.number"
          :poster="episode.poster"
          :is-seen="episode.viewable.isSeenByUser"
        />
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import ComicEpisodeItem from "@/components/Comic/ComicEpisodeItem.vue";
defineProps<{
  comicSlug: string;
  episodes: {
    title: string;
    publishedAt: string;
    number: number | string;
    poster: {
      medium: string;
    };
    viewable: {
      viewsCachedCount: number;
      isSeenByUser: boolean;
    };
  }[];
}>();
</script>

<style scoped>
.comic-episodes {
  display: flex;
  flex-direction: column;
  align-items: stretch;
}

.comic-episodes__item-wrapper {
  margin-bottom: 1px;
}
</style>
