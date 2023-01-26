<template>
  <div v-if="null !== currentUser" class="comic-user-list-select">
    <div @click="showDropdown = true" class="comic-user-list-select__btn btn w-100">
      <div class="comic-user-list-select__btn__text">
        {{ selectedComicUserList.name }}
      </div>
      <div class="comic-user-list-select__btn__icon">
        <IconTriangle direction="down" :width="10" :height="7" :fillColor="buttonColor" />
      </div>
    </div>
    <div class="comic-user-list-select__dropdown z-index-popup">
      <Transition name="slide-fade">
        <div
          v-if="showDropdown"
          v-detect-outer-click="onOuterClick"
          class="comic-user-list-select__dropdown__content round-angles-small shadow-smooth-small"
        >
          <div
            v-for="comicUserList in comicUserListsOptions"
            :key="comicUserList.id"
            @click="setComicUserList(comicUserList.slug)"
            class="comic-user-list-select__dropdown__content__list-option"
          >
            <HexColoredCircle :color="comicUserList.color" />
            <div class="comic-user-list-select__dropdown__content__list-option__name">
              {{ comicUserList.name }}
            </div>
          </div>
        </div>
      </Transition>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from "vue";
import IconTriangle from "@/components/Icons/IconTriangle.vue";
import { useAuth } from "@/composable/useAuth";
import { getValidHexColor } from "@/util/colors";
import axios from "axios";
import { BACKEND_URL } from "@/config";
import HexColoredCircle from "@/components/Util/HexColoredCircle.vue";

const props = defineProps<{
  initialComicUserListSlug: null | string;
  comicId: number;
}>();

const currentComicUserListSlug = ref<null | string>(null);

watch(
  () => props.initialComicUserListSlug,
  () => {
    currentComicUserListSlug.value = props.initialComicUserListSlug;
  },
  { immediate: true }
);

let isLocked = false;
const setComicUserList = (comicUserListSlug: string) => {
  if (isLocked) return;

  showDropdown.value = false;
  if (comicUserListSlug === currentComicUserListSlug.value) return;

  isLocked = true;
  let valueBeforeChange = currentComicUserListSlug.value;
  currentComicUserListSlug.value = comicUserListSlug;

  axios
    .post(`${BACKEND_URL}/api/comic-user-lists/${comicUserListSlug}/put-comic/${props.comicId}`)
    .catch(() => {
      currentComicUserListSlug.value = valueBeforeChange;
    })
    .then(() => {
      isLocked = false;
    });
};

const { currentUser } = useAuth();

const showDropdown = ref(false);

const onOuterClick = () => {
  showDropdown.value = false;
};

const NOT_IN_A_LIST_OPTION = { id: 0, color: "", name: "not in a list", slug: "not-in-a-list" };

const selectedComicUserList = computed(
  () =>
    comicUserListsOptions.value.find((l) => (l.slug ?? 0) === (currentComicUserListSlug.value ?? 0)) ??
    NOT_IN_A_LIST_OPTION
);

const comicUserListsOptions = computed(() => {
  const lists = [NOT_IN_A_LIST_OPTION];

  if (null === currentUser.value) return lists;
  lists.push(...[...currentUser.value.comicUserLists].sort((a, b) => a.id - b.id));

  return lists;
});

const buttonColor = computed(() =>
  getValidHexColor(selectedComicUserList.value && selectedComicUserList.value.color, "#3D3D3D")
);
</script>

<style scoped>
.slide-fade-leave-active,
.slide-fade-enter-active {
  transition: all 0.2s ease-out;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(10px);
  opacity: 0;
}

.comic-user-list-select {
  position: relative;
}
.comic-user-list-select__btn {
  display: flex;
  position: relative;
  align-items: center;
  justify-content: space-around;
  border-width: 1px;
  border-style: solid;
  border-color: v-bind(buttonColor);
  color: v-bind(buttonColor);
}

.comic-user-list-select__btn__text {
  font-size: 12px;
  font-weight: 500;
  text-transform: uppercase;
  display: flex;
  justify-content: center;
  flex: auto;
}

.comic-user-list-select__btn__icon {
  padding-right: 7px;
}

.comic-user-list-select__dropdown {
  width: 100%;
  left: 0;
  position: absolute;
  padding-top: 5px;
}

.comic-user-list-select__dropdown__content {
  width: 100%;
  background: white;
  padding: 5px 0;
}

.comic-user-list-select__dropdown__content__list-option:hover {
  background: #f1f1f1;
}

.comic-user-list-select__dropdown__content__list-option {
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2px 0;
}

.comic-user-list-select__dropdown__content__list-option__name {
  padding-left: 5px;
  text-transform: uppercase;
  font-size: 14px;
  padding-top: 2px;
}

.like-button__number {
  line-height: 10px;
  font-size: 14px;
  font-weight: 500;
  user-select: none;
}
</style>
