import { ref, reactive } from "vue";
import { BACKEND_URL } from "@/config";
import axios from "axios";
import type ComicCard from "@/api/Resources/Types/ComicCard";
import { CursorPaginatedResourceCollection } from "@/api/CursorPaginatedResourceCollection";
import { parseComicCard } from "@/api/Resources/Types/ComicCard";

export function useCatalogComicCards() {
  const comicCards = reactive(new CursorPaginatedResourceCollection<ComicCard>());

  const isLoading = ref(false);
  const loadedParams = ref({});

  // prettier-ignore
  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  const pushComicCards = function (responseData: any) {
    /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
    const itemsToPush: ComicCard[] = (responseData.data as any[]).map(
      (comicCard) => parseComicCard(comicCard)
    );

    comicCards.pushCursor(itemsToPush, responseData.links.next);
  };

  const fetchComicCardsPage = function (params: Record<string, unknown>) {
    isLoading.value = true;
    loadedParams.value = params;

    let url = `${BACKEND_URL}/api/catalog`;

    if (comicCards.cursorWasPushedAtLeastOnce()) {
      if (!comicCards.doesCursorHaveMore()) return;
      // use cursor link from collection instead
      url = comicCards.getNextCursor() as string;
    }

    axios.get(url, { params }).then(({ data }) => {
      pushComicCards(data);
      isLoading.value = false;
    });
  };

  const fetchNextPage = function () {
    fetchComicCardsPage(loadedParams.value);
  };

  const fetchFirstPage = function (params: Record<string, unknown>) {
    comicCards.clear();
    fetchComicCardsPage(params);
  };

  return {
    comicCards,
    fetchFirstPage,
    fetchNextPage,
    isLoading,
    loadedParams,
  };
}
