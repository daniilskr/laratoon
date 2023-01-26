import { ref, reactive, readonly } from "vue";
import { BACKEND_URL } from "@/config";
import axios from "axios";
import type ComicUserListEntry from "@/api/Resources/Types/ComicUserListEntry";
import { CursorPaginatedResourceCollection } from "@/api/CursorPaginatedResourceCollection";
import { parseComicUserListEntry } from "@/api/Resources/Types/ComicUserListEntry";

export function useComicUserListEntries() {
  const entries = reactive(new CursorPaginatedResourceCollection<ComicUserListEntry>());

  const isLoading = ref(false);
  const loadedComicUserListId = ref<null | number>(null);

  // prettier-ignore
  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  const pushEntries = function (responseData: any) {
    /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
    const itemsToPush: ComicUserListEntry[] = (responseData.data as any[]).map(
      (entry) => parseComicUserListEntry(entry)
    );

    entries.pushCursor(itemsToPush, responseData.links.next);
  };

  const fetchEntriesPage = function (comicUserListId: number) {
    if (!comicUserListId) return;

    isLoading.value = true;
    loadedComicUserListId.value = comicUserListId;

    let url = `${BACKEND_URL}/api/comic-user-lists/${comicUserListId}/entries`;

    if (entries.cursorWasPushedAtLeastOnce()) {
      if (!entries.doesCursorHaveMore()) return;
      // use cursor link from collection instead
      url = entries.getNextCursor() as string;
    }

    axios.get(url).then(({ data }) => {
      if (comicUserListId == loadedComicUserListId.value) {
        pushEntries(data);
        isLoading.value = false;
      }
    });
  };

  const fetchNextPage = function () {
    if (!(null === loadedComicUserListId.value)) {
      fetchEntriesPage(loadedComicUserListId.value);
    }
  };

  const fetchFirstPage = function (comicUserListId: number) {
    entries.clear();
    fetchEntriesPage(comicUserListId);
  };

  const refetchEntries = function () {
    if (loadedComicUserListId.value) {
      entries.clear();
      fetchFirstPage(loadedComicUserListId.value);
    }
  };

  const reset = function () {
    entries.clear();
    loadedComicUserListId.value = null;
  };

  return {
    entries,
    fetchFirstPage,
    fetchNextPage,
    refetchEntries,
    loadedComicUserListId: readonly(loadedComicUserListId),
    isLoading,
    reset,
  };
}
