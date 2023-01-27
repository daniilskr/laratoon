<template>
  <div class="page catalog-page">
    <div class="catalog-page__header-navbar" ref="refHeaderNavbar">
      <MainHeaderNavbar />
    </div>

    <div class="catalog-page__wrapper limited-to-content-width">
      <div class="catalog-page__aside">
        <div class="catalog-page__aside__header padding-x-15 padding-y-10">
          <h1 class="navbar-page-title text-left color-accent-5">Catalog</h1>
        </div>
        <div class="catalog-page__comic-cards-grid-square-cards padding-x-10 padding-y-10">
          <ComicCardsGridSquareCards :comic-cards="comicCards.items" />
          <div v-if="!isLoading && comicCards.items.length === 0" class="small-bold-title padding-t-10 text-center">
            No comics matched your query...
          </div>
          <ViewportIntersectionObserver
            v-show="!isLoading && comicCards.doesCursorHaveMore()"
            @intersects="fetchNextPage"
            :restore-on-change-of="comicCards.items.length"
          />
          <div v-if="isLoading" class="catalog-page__loading">
            <LoadingSpinner :delay-ms="300" />
          </div>
        </div>
      </div>
      <div class="catalog-page__sidebar">
        <div class="catalog-page__sidebar__filter sticky-to-top-element" ref="refStickySidebar">
          <CatalogFilters />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, nextTick, watch } from "vue";
import { useRoute } from "vue-router";
import MainHeaderNavbar from "@/components/MainHeaderNavbar.vue";
import CatalogFilters from "@/components/CatalogFilters.vue";
import ComicCardsGridSquareCards from "@/components/Comic/ComicCardsGridSquareCards.vue";
import ViewportIntersectionObserver from "@/components/Util/ViewportIntersectionObserver.vue";
import LoadingSpinner from "@/components/Util/LoadingSpinner.vue";
import { useCatalogComicCards } from "@/composable/catalog/useCatalogComicCards";

const refHeaderNavbar = ref<HTMLElement | null>(null);
const refStickySidebar = ref<HTMLElement | null>(null);

// prettier-ignore
const { isLoading, comicCards, fetchFirstPage, fetchNextPage } = useCatalogComicCards();

const route = useRoute();
watch(
  () => route.query,
  (q) => {
    if (route.name === "catalog.") fetchFirstPage(q);
  },
  { immediate: true }
);

// <layout stuff>
onMounted(() => nextTick(() => calculateNavbarHeightDependants()));

const calculateNavbarHeightDependants = () => {
  if (refStickySidebar.value && refHeaderNavbar.value) {
    refStickySidebar.value.style.top = `${refHeaderNavbar.value.offsetHeight}px`;
    refStickySidebar.value.style.height = `calc(100vh - ${refHeaderNavbar.value.offsetHeight}px)`;
  }
};
// </layout stuff>
</script>

<style scoped>
.catalog-page__sidebar__filter {
  box-sizing: border-box;
}

.catalog-page__wrapper {
  display: flex;
}

.catalog-page__wrapper > div:first-child {
  margin-right: 1px;
}

.catalog-page__loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-bottom: 90vh;
  padding-top: 25px;
}

.catalog-page__aside {
  flex: 1;
}

.catalog-page__aside__header {
  background: white;
}

.catalog-page__comic-cards-grid-square-cards {
  margin-top: 1px;
  background: white;
}

.catalog-page__sidebar {
  width: 320px;
  background: white;
}
</style>
