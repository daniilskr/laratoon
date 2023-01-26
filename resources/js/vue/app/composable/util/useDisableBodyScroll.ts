import { ref } from "vue";
import { useMeasureScrollbarWidth } from "@/composable/util/useMeasureScrollbarWidth";

const scrollDisabled = ref(false);
const { scrollbarWidth } = useMeasureScrollbarWidth();

export const useDisableBodyScroll = function () {
  const getBodyElement = () => {
    return document.querySelector("body");
  };

  const restoreScroll = () => {
    if (!scrollDisabled.value) return;
    const bodyElement = getBodyElement();
    if (bodyElement) {
      bodyElement.style.overflowY = "";
      scrollDisabled.value = false;
      bodyElement.style.paddingRight = "";
    }
  };

  const disableScroll = () => {
    if (scrollDisabled.value) return;

    const bodyElement = getBodyElement();
    if (bodyElement) {
      bodyElement.style.overflowY = "hidden";
      scrollDisabled.value = true;
      bodyElement.style.paddingRight = `${scrollbarWidth.value}px`;
    }
  };

  return {
    disableScroll,
    restoreScroll,
    scrollDisabled,
  };
};
