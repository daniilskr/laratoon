<template>
  <div class="comic-bookmarks">
    <div v-for="bookmark in bookmarks.items" :key="bookmark.id" class="comic-bookmarks__item-wrapper">
      <ComicBookmarksItem
        :episode="bookmark.episode"
        :page="bookmark.page"
        :note="bookmark.note"
        :color="bookmark.color"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive } from "vue";
import ComicBookmarksItem from "@/components/Comic/ComicBookmarksItem.vue";

const bookmarks = reactive({
  items: [
    {
      id: 4,
      episode: "Ep. 282",
      page: "12",
      note: "A moment when something happens, i guess",
      color: "#F9E74B",
    },
    {
      id: 3,
      episode: "Ep. 281",
      page: "12",
      note: "A moment when something happens, i guess",
      color: "#FA9952",
    },
    {
      id: 2,
      episode: "Ep. 280",
      page: "12",
      note: "A moment when something happens, i guess",
      color: "#2DBCDC",
    },
    {
      id: 1,
      episode: "Ep. 279",
      page: "12",
      note: "A moment when something happens, i guess",
      color: "#14DF71",
    },
    {
      id: 0,
      episode: "Ep. 278",
      page: "12",
      note: "A moment when something happens, i guess",
      color: "#E5BFF7",
    },
  ],
});
</script>

<style scoped>
.comic-bookmarks {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  margin-top: -5px;
}

.comic-bookmarks__item-wrapper {
  margin-bottom: 1px;
}
</style>
