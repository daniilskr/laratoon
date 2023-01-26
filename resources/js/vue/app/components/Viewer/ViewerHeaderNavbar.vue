<template>
  <div class="viewer-header-navbar header z-index-header-navbar padding-x-25">
    <div class="viewer-header-navbar__content limited-to-viewer-width">
      <div v-if="comicSlug && comicTitle" class="viewer-header-navbar__block viewer-header-navbar__block-left">
        <router-link
          :to="{ name: 'comic.', params: { comicSlug } }"
          class="viewer-header-nabvar__comic navbar-page-exact-active-link navbar-page-link"
        >
          {{ comicTitle }} #{{ episodeNumber }}
        </router-link>
      </div>
      <div class="viewer-header-navbar__block viewer-header-navbar__block-right">
        <div v-if="currentUser">
          <router-link
            :to="{ name: 'user.', params: { user: currentUser.id } }"
            class="main-header-nabvar__page-link navbar-page-link"
          >
            {{ currentUser ? currentUser.email : "logged out" }}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from "@/composable/useAuth";
const { currentUser } = useAuth();

defineProps<{
  comicSlug: null | string;
  comicTitle: null | string;
  episodeNumber: null | number;
}>();
</script>

<style scoped>
.viewer-header-nabvar__comic {
  color: black;
  display: flex;
  align-items: center;
}

.viewer-header-navbar {
  background: white;
}

.viewer-header-navbar__content {
  display: flex;
  align-items: center;
  position: relative;
}

.viewer-header-navbar__block {
  flex: 1;
  display: flex;
  z-index: 1;
}

.viewer-header-navbar__block-right {
  justify-content: flex-end;
}
</style>
