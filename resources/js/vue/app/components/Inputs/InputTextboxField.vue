<template>
  <div class="input-textbox-field" @click="focusOnRealInput">
    <div
      :class="[
        'input-textbox-field__input-wrapper',
        { 'input-textbox-field__input-wrapper--in-focus': isRealInputInFocus },
      ]"
    >
      <input
        :placeholder="placeholder"
        :value="modelValue"
        @input="emit('update:modelValue', $event.target.value)"
        @focus="isRealInputInFocus = true"
        @blur="isRealInputInFocus = false"
        ref="refRealInput"
        class="input-textbox-field__input"
        role="textbox"
        aria-multiline="true"
      />
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from "vue";

const refRealInput = ref<HTMLInputElement | null>(null);

defineProps<{
  placeholder?: string;
  modelValue?: string;
}>();

const emit = defineEmits<{
  (e: "update:modelValue", val: string): void;
}>();

const isRealInputInFocus = ref(false);

const focusOnRealInput = () => {
  if (refRealInput.value) refRealInput.value.focus();
};
</script>

<style scoped>
.input-textbox-field__input-wrapper {
  position: relative;
  width: 100%;
  border: solid 1px #f1f1f1;
  border-radius: 5px;
  padding: 0 5px;
  box-sizing: border-box;
  background: #f2f2f2;
  cursor: text;
}

.input-textbox-field__input-wrapper:hover {
  border: solid 1px #d9d9d9;
}

.input-textbox-field__input-wrapper.input-textbox-field__input-wrapper--in-focus {
  border: solid 1px #d9d9d9;
  background: white;
}

.input-textbox-field__input {
  background: #f2f2f2;
  width: 100%;
  border: none;
  outline: none;
}

.input-textbox-field__input-wrapper--in-focus .input-textbox-field__input {
  background: white;
}
</style>
