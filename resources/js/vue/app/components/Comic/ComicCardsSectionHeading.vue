<template>
  <div class="comic-cards-section-heading heading-section-height">
    <div class="comic-cards-section-heading__background">
      <div
        :style="`background: url('${sectionPoster.medium}') no-repeat center`"
        class="comic-cards-section-heading__background__img"
      />
    </div>
    <div class="comic-cards-section-heading__front padding-x-5">
      <div class="comic-cards-section-heading__wrapper limited-to-content-width">
        <h1 :class="['comic-cards-section-heading__title', titleClass]">{{ sectionTitle }}</h1>
        <div class="comic-cards-section-heading__cards-list">
          <div
            v-for="comicCard in comicCards"
            :key="comicCard.id"
            class="comic-cards-section-heading__cards-list__card-wrapper shadow-smooth-small round-angles-small"
          >
            <router-link
              class="comic-cards-section-small-wide-cards__link"
              :to="{ name: 'comic.', params: { comic: comicCard.slug } }"
            >
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
      </div>
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
  }[],
  sectionTitle: string;
  titleClass: string;
  sectionPoster: {
    medium: string,
  },
}>();
</script>

<style scoped>
.comic-cards-section-heading {
  position: relative;
}

.comic-cards-section-heading__background {
  z-index: -1;
  width: 100%;
  height: 100%;
  overflow: hidden;
  filter: brightness(0.9);
}

.comic-cards-section-heading__background__img {
  width: 100%;
  height: 100%;
}

.comic-cards-section-heading__front {
  top: 0;
  z-index: 9;
  position: absolute;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
}

.comic-cards-section-heading__cards-list {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.comic-cards-section-heading__cards-list__card-wrapper {
  width: calc(20% - 15px);
}

.comic-cards-section-heading__wrapper {
  flex-shrink: 0;
}
</style>
