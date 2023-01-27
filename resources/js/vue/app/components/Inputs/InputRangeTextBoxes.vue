<template>
  <div class="input-range-textboxes">
    <div class="input-range-textboxes__input-wrapper">
      <InputTextboxField v-model="from" :placeholder="fromPlaceholder" />
    </div>
    <div class="input-range-textboxes__line">-</div>
    <div class="input-range-textboxes__input-wrapper">
      <InputTextboxField v-model="to" :placeholder="toPlaceholder" />
    </div>
  </div>
</template>

<script setup lang="ts">
import InputTextboxField from "@/components/Inputs/InputTextboxField.vue";
import { computed } from "@vue/reactivity";

const props = defineProps<{
  fromPlaceholder?: string;
  toPlaceholder?: string;
  fromValue?: string;
  toValue?: string;
}>();

const emit = defineEmits<{
  (e: "update:fromValue", val: string): void;
  (e: "update:toValue", val: string): void;
}>();

const from = computed({
  get() {
    return props.fromValue as string;
  },
  set(val: string) {
    emit("update:fromValue", val);
  },
});

const to = computed({
  get() {
    return props.toValue as string;
  },
  set(val: string) {
    emit("update:toValue", val);
  },
});
</script>

<style scoped>
.input-range-textboxes {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.input-range-textboxes__line {
  padding: 0 5px;
  font-size: 24px;
  color: #cecece;
  line-height: 10px;
}
</style>
