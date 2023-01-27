export const detectOuterClick = {
  mounted(el, { value: callback }) {
    el.detectOuterClickEventListener = function (event) {
      if (!el.contains(event.target)) callback(event);
    };

    // Черная магия, чтобы эвент не сработал раньше времени (из-за всплытия)
    setTimeout(() => {
      document.body.addEventListener("click", el.detectOuterClickEventListener);
    }, 0);
  },

  unmounted(el) {
    document.body.removeEventListener("click", el.detectOuterClickEventListener);
  },
};
