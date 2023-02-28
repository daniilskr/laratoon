<template>
  <div class="page home-page">
    <div class="home-page__header-navbar">
      <MainHeaderNavbar />
    </div>

    <div class="home-page__wrapper">
      <div class="home-page__content">
        <template v-for="section in sections" :key="section.title">
          <div
            v-if="section.type == ComicCardSectionType.SmallWideCards"
            class="padding-xy-25 limited-to-content-width bg-color-space-1"
          >
            <h1 class="thick-uppercase-shrinked-title text-left text-color-accent-5 padding-b-25">
              {{ section.title }}
            </h1>
            <ComicCardsSectionSmallWideCards :comic-cards="section.comicCards" />
          </div>

          <ComicCardsSectionHeading
            v-if="section.type == ComicCardSectionType.Heading"
            title-class="thick-uppercase-title text-left text-color-space-1 padding-b-25"
            :section-title="section.title"
            :section-poster="section.sectionPoster"
            :comic-cards="section.comicCards"
          />

          <ComicCardsSectionGoToMore
            v-if="section.type == ComicCardSectionType.GoToMore"
            title-class="thick-uppercase-title text-left text-color-space-1 padding-b-25"
            class="limited-to-content-width bg-color-space-1"
            :section-title="section.title"
            :section-poster="section.sectionPoster"
            :comic-cards="section.comicCards"
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
import { ComicCardSectionType, parseComicCardSection } from "@/api/Resources/Types/ComicCardSection";
import { BACKEND_URL } from "@/config";
import axios from "axios";

const sections = ref<ComicCardSection[]>([]);

axios.get(`${BACKEND_URL}/api/home-comic-cards-sections`).then((response) => {
  sections.value = (response.data as any[]).map((comicCardSection) => parseComicCardSection(comicCardSection));
});
</script>
