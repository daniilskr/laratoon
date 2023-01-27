<template>
  <div class="comic-characters">
    <div v-for="characterRole in charactersRoles" :key="characterRole.id" class="comic-characters__item-wrapper">
      <ComicCharacterCard
        :id="characterRole.character.id"
        :name="characterRole.character.fullName"
        :note="characterRole.description"
        :img="characterRole.character.poster.medium"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import ComicCharacterCard from "@/components/Comic/ComicCharacterCard.vue";
defineProps<{
  charactersRoles: {
    id: number;
    description: string;
    character: {
      id: number;
      fullName: string;
      poster: {
        medium: string;
      };
    };
  }[];
}>();
</script>

<style scoped>
.comic-characters {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
  margin-top: -3px;
}
</style>
