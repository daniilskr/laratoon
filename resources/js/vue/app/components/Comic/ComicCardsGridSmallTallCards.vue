<template>
  <div class="comic-cards-grid-square-cards">
    <div v-for="card in props.cards" :key="card.id" class="comic-cards-grid-square-cards__card-wrapper">
      <router-link :to="card.linkTo">
        <ComicCardSmallTall
          :author="card.author"
          :title="card.title"
          :main="card.main"
          :additional="card.additional"
          :image="card.image"
        />
      </router-link>
    </div>
  </div>
</template>

<script setup lang="ts">
import ComicCardSmallTall from "@/components/Comic/ComicCardSmallTall.vue";
const props = defineProps<{
  cards: {
    id: number;
    image: {
      medium: string;
    };
    slug: string;
    author: string;
    title: string;
    main: string;
    additional: string;
    linkTo: {
      name: string;
      params: Record<string, unknown>;
    };
  }[];
}>();
</script>

<style scoped>
.comic-cards-grid-square-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
}

.comic-cards-grid-square-cards__card-wrapper {
  padding: 15px;
}
</style>
