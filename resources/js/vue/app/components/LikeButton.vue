<template>
  <div class="like-button">
    <div @click="toggle" class="like-button__heart-icon-wrapper">
      <IconHeart :width="15" :height="15" :stroke-color="'#2DBCDC'" :fill-color="isLiked ? '#2DBCDC' : 'none'" />
    </div>
    <div class="like-button__number text-color-accent-5">
      {{ computedLikesCount }}
    </div>
  </div>
</template>

<script setup lang="ts">
import IconHeart from "@/components/Icons/IconHeart.vue";
import { ref, watch, computed } from "vue";
import axios from "axios";
import { BACKEND_URL } from "@/config";
import { useAuth } from "@/composable/useAuth";

const props = defineProps<{
  likesCachedCount: number;
  likeableId: number;
  isInitiallyLiked: boolean;
}>();

const { currentUser } = useAuth();
const isAuthenticated = computed(() => !(null === currentUser.value));

let isLiked = ref(false);
const computedLikesCount = computed(() => {
  if (props.isInitiallyLiked) {
    return props.likesCachedCount + (isLiked.value ? 0 : -1);
  } else {
    return props.likesCachedCount + (isLiked.value ? 1 : 0);
  }
});

watch(
  () => props.likeableId,
  () => {
    isLiked.value = props.isInitiallyLiked;
  },
  { immediate: true }
);

const emit = defineEmits<{
  (e: "liked"): void;
  (e: "unliked"): void;
}>();

let isLocked = false;

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
const failedToLike = (err: any) => {
  console.log(err);
};

const toggle = () => {
  if (isLocked || !isAuthenticated.value) return;

  isLocked = true;
  // prettier-ignore
  (isLiked.value ? unlike() : like())
    .catch(failedToLike)
    .then(() => {
      isLocked = false;
    });
};

const like = () => {
  isLiked.value = true;
  emit("liked");
  return axios.post(`${BACKEND_URL}/api/likeables/${props.likeableId}/like`);
};

const unlike = () => {
  isLiked.value = false;
  emit("unliked");
  return axios.delete(`${BACKEND_URL}/api/likeables/${props.likeableId}/unlike`);
};
</script>

<style scoped>
.like-button {
  margin: 0;
  display: flex;
  align-items: center;
}

.like-button__heart-icon-wrapper {
  padding-right: 5px;
  padding-bottom: 3px;
  cursor: pointer;
}

.like-button__number {
  line-height: 10px;
  font-size: 14px;
  font-weight: 500;
  user-select: none;
}
</style>
