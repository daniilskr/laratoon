<template>
  <div class="catalog-filters">
    <div class="catalog-filters__filters-scrollbox custom-scrollbar">
      <div class="catalog-filters__filters-scrollbox__wrapper padding-x-15 padding-t-15">
        <div class="catalog-filters__filter-wrapper">
          <CollapsableFilterBlock
            :title="'Genre' + (activeFiltersSlugs.genres ? ` (${activeFiltersSlugs.genres.length})` : '')"
            is-initially-collapsed
          >
            <InputLabeledCheckbox
              v-for="genre in filters.genres"
              :key="genre.id"
              :label="wordsToUpper(genre.name)"
              v-model="genre.isChecked"
              class="padding-t-5"
            />
          </CollapsableFilterBlock>
        </div>
        <div class="catalog-filters__filter-wrapper">
          <CollapsableFilterBlock
            :title="'Tags' + (activeFiltersSlugs.tags ? ` (${activeFiltersSlugs.tags.length})` : '')"
            is-initially-collapsed
          >
            <InputLabeledCheckbox
              v-for="tag in filters.tags"
              :key="tag.id"
              :label="wordsToUpper(tag.name)"
              v-model="tag.isChecked"
              class="padding-t-5"
            />
          </CollapsableFilterBlock>
        </div>
        <div class="catalog-filters__filter-wrapper">
          <div class="small-bold-title text-left padding-b-5">Status</div>
          <InputLabeledCheckbox
            v-for="status in filters.statuses"
            :key="status.name"
            :label="wordsToUpper(status.name)"
            v-model="status.isChecked"
            class="padding-t-5"
          />
        </div>
        <div class="catalog-filters__filter-wrapper">
          <div class="small-bold-title text-left padding-b-5">Year</div>
          <InputRangeTextBoxes
            v-model:from-value="filters.year_from"
            from-placeholder="from"
            v-model:to-value="filters.year_to"
            to-placeholder="to"
          />
        </div>
      </div>
    </div>
    <div class="catalog-filters__buttons padding-xy-15">
      <button @click="applyFiltersToRoute()" class="btn btn-good">apply</button>
      <button @click="removeFiltersFromRoute()" class="btn btn-outline-neutral">reset</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { unref, ref, watch, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import CollapsableFilterBlock from "@/components/CollapsableFilterBlock.vue";
import InputLabeledCheckbox from "@/components/Inputs/InputLabeledCheckbox.vue";
import InputRangeTextBoxes from "@/components/Inputs/InputRangeTextBoxes.vue";
import axios from "axios";
import { BACKEND_URL } from "@/config";
import { wordsToUpper } from "@/util/strings";
import _ from "lodash";

const filters = ref({
  tags: new Array<{ id: number; name: string; slug: string; isChecked: boolean }>(),
  genres: new Array<{ id: number; name: string; slug: string; isChecked: boolean }>(),
  statuses: new Array<{ id: number; name: string; slug: string; isChecked: boolean }>(),
  year_from: "",
  year_to: "",
});

const route = useRoute();
const router = useRouter();

const syncFiltersWithRouteQuery = () => {
  ["tags", "statuses", "genres"].forEach((propName) => {
    filters.value[propName as "tags" | "statuses" | "genres"].forEach((checkable) => {
      // prettier-ignore
      checkable.isChecked = _.has(route.query, propName) && _.includes(route.query[propName], checkable.slug) ? true : false;
    });
  });

  filters.value.year_from = _.has(route.query, "year_from") ? (route.query.year_from as string) : "";
  filters.value.year_to = _.has(route.query, "year_to") ? (route.query.year_to as string) : "";
};

watch(
  () => route.query,
  () => {
    if (route.name === "catalog.") {
      syncFiltersWithRouteQuery();
    }
  }
);

const activeFiltersSlugs = computed(() => {
  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  const active: any = {};

  const tags = filters.value.tags.filter((i) => i.isChecked).map((i) => i.slug);
  if (tags.length > 0) active.tags = tags;

  const genres = filters.value.genres.filter((i) => i.isChecked).map((i) => i.slug);
  if (genres.length > 0) active.genres = genres;

  const statuses = filters.value.statuses.filter((i) => i.isChecked).map((i) => i.slug);
  if (statuses.length > 0) active.statuses = statuses;

  if (filters.value.year_from) active.year_from = filters.value.year_from;
  if (filters.value.year_to) active.year_to = filters.value.year_to;

  return active;
});

const applyFiltersToRoute = () => {
  router.push({
    name: "catalog.",
    query: unref(activeFiltersSlugs),
  });
};

const removeFiltersFromRoute = () => {
  if (_.isEmpty(route.query)) {
    syncFiltersWithRouteQuery();
  } else {
    router.push({ name: "catalog." });
  }
};

axios.get(`${BACKEND_URL}/api/catalog-filters`).then((response) => {
  ["tags", "statuses", "genres"].forEach((propName) => {
    filters.value[propName as "tags" | "statuses" | "genres"] = response.data[propName].map(
      (i: { id: number; name: string; slug: string }) => ({
        ...i,
        isChecked: false,
      })
    );
  });

  syncFiltersWithRouteQuery();
});
</script>

<style scoped>
.catalog-filters {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.catalog-filters__filter-wrapper {
  padding-bottom: 10px;
}

.catalog-filters__filters-scrollbox {
  flex-shrink: 1;
  overflow-y: auto;
}

.catalog-filters__buttons {
  flex-shrink: 0;
  flex-grow: 1;
  display: flex;
  justify-content: space-between;
}

.catalog-filters__buttons > button {
  width: 48%;
}
</style>
