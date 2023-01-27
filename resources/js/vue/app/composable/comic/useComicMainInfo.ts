import axios from "axios";
import { ref } from "vue";
import { BACKEND_URL } from "@/config";
import type ComicMainInfo from "@/api/Resources/Types/ComicMainInfo";
import { parseComicMainInfo } from "@/api/Resources/Types/ComicMainInfo";

export function useComicMainInfo() {
  const comicMainInfo = ref<null | ComicMainInfo>(null);

  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  const setComicMainInfo = (info: any) => {
    comicMainInfo.value = parseComicMainInfo(info);
  };

  const isLoading = ref(false);
  let comicToLoad: null | string = null;

  const fetchComicMainInfo = (comicId: string) => {
    if (!comicId) return;

    if (isLoading.value && comicToLoad === comicId) {
      // already loading what's needed
      return;
    }
    isLoading.value = true;
    comicToLoad = comicId;

    const apiResource = /^[0-9]+$/.test(comicId) ? "comics" : "comic-by-slug";

    axios
      .get(`${BACKEND_URL}/api/${apiResource}/${comicId}/main-info`)
      .then(({ data }) => {
        if (comicToLoad === comicId) {
          setComicMainInfo(data.data);
        }
      })
      .catch((err) => {
        console.log(err);
      })
      .then(() => {
        if (comicToLoad === comicId) {
          isLoading.value = false;
        }
      });
  };

  return {
    isLoading,
    comicMainInfo,
    fetchComicMainInfo,
  };
}
