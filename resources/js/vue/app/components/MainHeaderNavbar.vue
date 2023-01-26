<template>
  <div>
    <div class="main-header-nabvar header z-index-header-navbar" ref="refRealNavbar">
      <div class="main-header-nabvar__wrapper limited-to-content-width padding-x-25--media-less-1280">
        <div class="main-header-nabvar__group main-header-nabvar__page-links">
          <!-- <router-link to="/" class="main-header-nabvar__page-link navbar-page-link navbar-page-exact-active-link">
            Home
          </router-link> -->
          <router-link class="main-header-nabvar__page-link navbar-page-link" to="/catalog">Catalog</router-link>
        </div>
        <div v-if="currentUser" class="main-header-nabvar__group main-header-nabvar__user-menu">
          <router-link
            :to="{ name: 'user.', params: { user: currentUser.id } }"
            class="main-header-nabvar__page-link navbar-page-link"
          >
            {{ currentUser ? currentUser.email : "logged out" }}
          </router-link>
        </div>
      </div>
    </div>
    <div class="main-header-nabvar_placeholder" ref="refNavbarPlaceholder"></div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick } from "vue";
import { useAuth } from "@/composable/useAuth";

const refNavbarPlaceholder = ref<HTMLElement | null>(null);
const refRealNavbar = ref<HTMLElement | null>(null);
const { currentUser } = useAuth();

onMounted(() =>
  nextTick(() => {
    if (refNavbarPlaceholder.value && refRealNavbar.value) {
      refNavbarPlaceholder.value.style.height = refRealNavbar.value.offsetHeight + "px";
    }
  })
);
</script>

<style scoped>
.main-header-nabvar {
  position: fixed;
  top: 0;
  width: 100%;
  background: white;
  border-bottom: solid 1px #f5f5f5;
}

.main-header-nabvar__wrapper {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}

.main-header-nabvar__group {
  flex-direction: row;
  display: flex;
  align-items: center;
}

.main-header-nabvar__page-link:last-child {
  margin-right: 0;
}

a.main-header-nabvar__page-link {
  padding-top: 11px;
  padding-bottom: 10px;
  margin-right: 20px;
}

.main-header-nabvar__user-menu > p {
  margin: 0px;
}
</style>
