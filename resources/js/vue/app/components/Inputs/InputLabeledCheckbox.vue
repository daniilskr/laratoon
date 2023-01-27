<template>
  <label class="input-labeled-checkbox">
    <input v-model="model" type="checkbox" class="input-labeled-checkbox__input-real" />
    <div class="input-labeled-checkbox__input-mimic">
      <div class="input-labeled-checkbox__input-mimic__checkmark">
        <IconCheckmark stroke-width="3" stroke-color="#2DBCDC" width="14" height="12" />
      </div>
    </div>
    <div class="input-labeled-checkbox__text">
      <slot>{{ label }}</slot>
    </div>
  </label>
</template>

<script setup lang="ts">
import IconCheckmark from "@/components/Icons/IconCheckmark.vue";
import { computed } from "@vue/reactivity";

const props = defineProps<{
  label: string;
  modelValue: boolean;
}>();

const emit = defineEmits<{
  (e: "update:modelValue", val: boolean): void;
}>();

const model = computed({
  get() {
    return props.modelValue as boolean;
  },
  set(val: boolean) {
    emit("update:modelValue", val);
  },
});
</script>

<style scoped>
.input-labeled-checkbox {
  user-select: none;
  cursor: pointer;
  text-align: left;
  padding-right: 5px;
  display: flex;
  align-items: flex-end;
}

.input-labeled-checkbox__input-real {
  position: absolute;
  z-index: -1;
  visibility: hidden;
  opacity: 0;
}

.input-labeled-checkbox__input-mimic {
  background: white;
  width: 14px;
  height: 14px;
  border: solid 1px #f1f1f1;
  border-radius: 3px;
  transition: background 0.1s;
  transition: border-color 0.1s;
}

.input-labeled-checkbox:hover .input-labeled-checkbox__input-mimic {
  border: solid 1px #d9d9d9;
}

.input-labeled-checkbox:hover .input-labeled-checkbox__text {
  color: #b1b1b1;
}

.input-labeled-checkbox__input-mimic__checkmark {
  display: flex;
  align-items: center;
  justify-content: space-around;
  height: 100%;
  width: 100%;
  transform: scale(0.7);
  opacity: 0;
  transition: transform 45ms cubic-bezier(0.18, 0.89, 0.32, 1.28);
}

.input-labeled-checkbox__input-real:checked
  ~ .input-labeled-checkbox__input-mimic
  > .input-labeled-checkbox__input-mimic__checkmark {
  transform: scale(1);
  opacity: 1;
}

.input-labeled-checkbox__text {
  line-height: 14px;
  font-size: 14px;
  padding-left: 7px;
}
</style>
