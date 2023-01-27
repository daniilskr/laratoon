<template>
  <div class="comic-cards-section-small-wide-cards">
    <div class="comic-cards-section-small-wide-cards__wrapper">
      <div class="comic-cards-section-small-wide-cards__cards-list">
        <router-link
          v-for="comicCard in comicCards"
          :key="comicCard.id"
          class="comic-cards-section-small-wide-cards__link"
          :to="{ name: 'comic.', params: { comic: comicCard.slug } }"
        >
          <ComicCardSmallWide
            :author="comicCard.author.fullName"
            :title="comicCard.title"
            :description="comicCard.description"
            :likes="comicCard.statistics.likes.total"
            :image="comicCard.comicPoster"
          />
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import ComicCardSmallWide from "@/components/Comic/ComicCardSmallWide.vue";
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
.comic-cards-section-small-wide-cards__link {
  text-decoration: none;
}

.comic-cards-section-small-wide-cards__wrapper {
  background: white;
}

.comic-cards-section-small-wide-cards__front {
  top: 0;
  z-index: 9;
  position: absolute;
  width: 100%;
  height: 100%;
  display: grid;
}

.comic-cards-section-small-wide-cards__cards-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  grid-auto-rows: minmax(100px, auto);
  row-gap: 25px;
}
</style>
