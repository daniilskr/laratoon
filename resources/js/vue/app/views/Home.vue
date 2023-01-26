<template>
  <div class="page home-page">
    <div class="home-page__header-navbar">
      <MainHeaderNavbar />
    </div>

    <div class="home-page__wrapper">
      <ComicCardsSectionHeading
        title-class="thick-uppercase-title text-left text-color-space-1 padding-b-25"
        section-title="Latest updates"
      />

      <div class="home-page__content limited-to-content-width bg-color-space-1">
        <template v-for="section in sections" :key="section.title">
          <div v-if="section.type == ComicCardSectionType.SmallWideCards" class="padding-xy-25">
            <h1 class="thick-uppercase-shrinked-title text-left text-color-accent-5 padding-b-25">Popular today</h1>
            <ComicCardsSectionSmallWideCards :comic-cards="section.comicCards" />
          </div>

          <ComicCardsSectionGoToMore
            v-if="section.type == ComicCardSectionType.GoToMore"
            title-class="thick-uppercase-title text-left text-color-space-1 padding-b-25"
            :section-title="section.title"
          />
        </template>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import MainHeaderNavbar from "@/components/MainHeaderNavbar.vue";
import ComicCardsSectionHeading from "@/components/Comic/ComicCardsSectionHeading.vue";
import ComicCardsSectionSmallWideCards from "@/components/Comic/ComicCardsSectionSmallWideCards.vue";
import ComicCardsSectionGoToMore from "@/components/Comic/ComicCardsSectionGoToMore.vue";
import type ComicCardSection from "@/api/Resources/Types/ComicCardSection";
import { ComicCardSectionType } from "@/api/Resources/Types/ComicCardSection";
import { BACKEND_URL } from "@/config";
import axios from "axios";

const sections = ref<ComicCardSection[]>([]);

axios.get(`${BACKEND_URL}/home-comic-cards-sections`).then((response) => {
  sections.value = response.data.data;
});
</script>
