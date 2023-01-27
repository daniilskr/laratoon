<template>
  <div class="comic-cards-grid-square-cards">
    <div v-for="comicCard in comicCards" :key="comicCard.id" class="comic-cards-grid-square-cards__card-wrapper">
      <router-link :to="{ name: 'comic.', params: { comic: comicCard.slug } }">
        <ComicCardSquare
          :author="comicCard.author.fullName"
          :title="comicCard.title"
          :description="comicCard.description"
          :likes="comicCard.statistics.likes.total"
          :img="comicCard.comicPoster.medium"
        />
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import ComicCardSquare from "@/components/Comic/ComicCardSquare.vue";
defineProps<{
  comicCards: {
    id: number;
    slug: string;
    title: string;
    description: string;
    author: {
      id: number;
      fullName: string;
    };
    comicPoster: {
      medium: string;
    };

    statistics: {
      likes: {
        total: number;
      };
    };
  }[];
}>();
</script>

<style scoped>
.comic-cards-grid-square-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  grid-auto-rows: minmax(100px, auto);
}

.comic-cards-grid-square-cards__card-wrapper {
  padding: 5px;
}
</style>
