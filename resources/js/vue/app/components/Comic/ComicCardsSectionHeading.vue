<template>
  <div class="comic-cards-section-heading heading-section-height">
    <div class="comic-cards-section-heading__background">
      <div
        :style="`background: url(${urlBackground}) no-repeat center`"
        class="comic-cards-section-heading__background__img"
      />
    </div>
    <div class="comic-cards-section-heading__front padding-x-5">
      <div class="comic-cards-section-heading__wrapper limited-to-content-width">
        <h1 :class="['comic-cards-section-heading__title', titleClass]">{{ sectionTitle }}</h1>
        <div class="comic-cards-section-heading__cards-list">
          <div
            v-for="i in 5"
            :key="i"
            class="comic-cards-section-heading__cards-list__card-wrapper shadow-smooth-small round-angles-small"
          >
            <router-link :to="{ name: 'comic.', params: { comic: `pepper-and-carrot-${i}` } }">
              <ComicCardSquare
                author="David Revoy"
                title="Pepper and Carrot"
                description="Pepper, a young witch and her cat, Carrot, live in a fun fantasy universe made of potions, magic and creatures"
                :likes="17000"
              />
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import urlBackground from "@/assets/cards-heading-bg-pepper-and-carrot.png";
import ComicCardSquare from "@/components/Comic/ComicCardSquare.vue";
defineProps(["sectionTitle", "titleClass"]);
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
