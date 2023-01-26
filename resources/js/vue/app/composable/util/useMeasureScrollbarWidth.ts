import { ref } from "vue";
const scrollbarWidthCache = ref(0);

const measureScrollbarWidth = function () {
  const div = document.createElement("div");
  div.style.opacity = "0";
  div.style.overflowY = "scroll";
  document.body.appendChild(div);
  const scrollbarWidth = div.offsetWidth - div.clientWidth;
  document.body.removeChild(div);

  return scrollbarWidth;
};

scrollbarWidthCache.value = measureScrollbarWidth();

export const useMeasureScrollbarWidth = function () {
  return {
    scrollbarWidth: scrollbarWidthCache,
  };
};
