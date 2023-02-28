<template>
  <div class="comic-cards-section-gotomore heading-section-height">
    <div class="comic-cards-section-gotomore__cards-wrapper">
      <div class="comic-cards-section-gotomore__background">
        <img :src="sectionPoster.medium" />
      </div>
      <div class="comic-cards-section-gotomore__front padding-xy-25">
        <div class="comic-cards-section-gotomore__front__content-wrapper">
          <h1 :class="['comic-cards-section-gotomore__title', titleClass]">{{ sectionTitle }}</h1>
          <div class="comic-cards-section-gotomore__cards-list">
            <div
              v-for="comicCard in comicCards"
              :key="comicCard.id"
              class="comic-cards-section-gotomore__cards-list__card-wrapper shadow-smooth-small round-angles-small"
            >
              <router-link
                class="comic-cards-section-gotomore__card-link"
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
    <router-link class="comic-cards-section-gotomore__more-link" to="/catalog">
      more <br />
      {{ sectionTitle }}
    </router-link>
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
  sectionTitle: string;
  titleClass: string;
  sectionPoster: {
    medium: string;
  };
}>();
</script>

<style scoped>
.comic-cards-section-gotomore {
  margin-bottom: 2px;
  display: flex;
}

.comic-cards-section-gotomore__cards-wrapper {
  position: relative;
  display: flex;
  box-sizing: border-box;
  overflow-x: hidden;
  flex: 1;
}

.comic-cards-section-gotomore__background {
  position: absolute;
  overflow: hidden;
  filter: brightness(0.9);
}

.comic-cards-section-gotomore__background > img {
  display: block;
}

.comic-cards-section-gotomore__front {
  z-index: 2;
  top: 0;
  z-index: 9;
  display: flex;
  align-items: center;
  box-sizing: border-box;
  flex: 1;
}

.comic-cards-section-gotomore__front__content-wrapper {
  flex: 1;
}

.comic-cards-section-gotomore__cards-list {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.comic-cards-section-gotomore__cards-list__card-wrapper {
  width: calc(25% - 15px);
}

.comic-cards-section-gotomore__more-link {
  z-index: 9;
  font-weight: bold;
  font-size: 25px;
  line-height: 25px;
  text-align: center;
  text-transform: uppercase;
  color: #0f0f33;
  background: white;
  width: 200px;
  display: grid;
  align-items: center;
  text-decoration: none;
}

.comic-cards-section-gotomore__more-link:hover {
  background: #f5f5f5;
}
</style>
