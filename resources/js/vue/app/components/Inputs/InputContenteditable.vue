<template>
  <div class="input-multiline-text">
    <div
      :class="[
        'input-multiline-text__input-wrapper',
        { 'input-multiline-text__input-wrapper--in-focus': isRealInputInFocus },
      ]"
    >
      <div
        ref="refRealInput"
        @input="onUpdate"
        @focus="isRealInputInFocus = true"
        @blur="onBlur"
        @paste="onPaste"
        :aria-label="props.placeholder"
        class="input-multiline-text__input"
        :contenteditable="!props.disabled"
        dir="auto"
      ></div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from "vue";

const props = defineProps<{
  placeholder: string;
  modelValue: string;
  disabled?: boolean;
  focusOnMount?: boolean;
}>();

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void;
}>();

const refRealInput = ref<null | HTMLDivElement>(null);
const getRealInputValue = () => refRealInput.value?.innerText ?? "";
const isRealInputInFocus = ref(false);

const onUpdate = () => {
  emit("update:modelValue", getRealInputValue());
};

const onPaste = (event: ClipboardEvent) => {
  event.preventDefault();
  const text = event.clipboardData?.getData("text/plain") ?? "";
  window.document.execCommand("insertText", false, text);
};

const onBlur = () => {
  isRealInputInFocus.value = false;
  onUpdate();
};

const updateRealInputValue = (newValue: string) => {
  if (refRealInput.value) refRealInput.value.innerText = newValue;
};

watch(
  () => props.modelValue,
  (newValue) => {
    // Защита от ре-рендера при обновлении props.modelValue от брошенного
    // пользовательским вводом эвента update:modelValue
    if (newValue === getRealInputValue()) return;

    updateRealInputValue(newValue);
  }
);

onMounted(() => {
  updateRealInputValue(props.modelValue ?? "");
  if (refRealInput.value && props.focusOnMount) refRealInput.value.focus();
});
</script>

<style scoped>
.input-multiline-text__input-wrapper {
  position: relative;
  width: 100%;
  border-bottom: dashed 2px #f1f1f1;
  transition: ease-in border-color 0.1s;
  box-sizing: border-box;
  cursor: text;
}

.input-multiline-text__input-wrapper.input-multiline-text__input-wrapper--in-focus {
  border-color: rgb(207, 205, 205);
}

.input-multiline-text__input[contenteditable="false"],
.input-multiline-text__input:not([contenteditable]) {
  word-wrap: break-word;
}

.input-multiline-text__input {
  font-family: Arial, Helvetica, sans-serif;
  width: 100%;
  border: none;
  outline: none;
  text-align: initial;
  max-height: none;
  height: auto;
  padding-bottom: 5px;
  --input-multiline-text-placeholder: attr(aria-label);
}

.input-multiline-text__input:before {
  overflow: hidden;
  color: #b3b3b3;
}

.input-multiline-text__input:empty:before {
  content: var(--input-multiline-text-placeholder);
}
</style>
