<template>
  <div
    @click.self="closePopup"
    :class="['popup popup-with-shadow z-index-popup-over-everything login-popup', { 'popup--closable': closable }]"
  >
    <div class="popup-window bg-color-space-1">
      <div class="popup-window__content">
        <h1 class="thick-uppercase-shrinked-title text-color-accent-5 padding-b-15">DEMO SESSION</h1>
        <div v-if="isAuthenticated">
          <p>You are logged in with a demo account. Feel free to interact with the application in any way you want</p>
          <div class="padding-t-15">
            <button @click.self="closePopup" class="btn btn-good w-25">OK</button>
          </div>
        </div>
        <div v-else>
          <p>
            <SkeletonLine :height="20" />
            We are setting up a demo session for you...
            <SkeletonLine :height="20" />
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { useDemoLogin } from "@/composable/demo/useDemoLogin";
import { useAuth } from "@/composable/useAuth";
import SkeletonLine from "@/components/Skeleton/SkeletonLine.vue";
import _ from "lodash";

const emit = defineEmits<{
  (e: "closePopup"): void;
}>();

const { loginWithDemoCredentials } = useDemoLogin();
const { isAuthenticated } = useAuth();
const closable = computed(() => !_.isNull(isAuthenticated.value));

const closePopup = () => {
  if (closable.value) emit("closePopup");
};

if (!isAuthenticated.value) {
  loginWithDemoCredentials();
}
</script>

<style scoped>
.popup-window {
  /* height: 250px; */
  width: 350px;
  cursor: default;
}

.popup-window__content {
  width: 100%;
  height: 100%;
  padding: 35px 25px;
}
</style>
