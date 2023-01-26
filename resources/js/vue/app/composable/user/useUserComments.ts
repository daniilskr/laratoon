import { ref, reactive, readonly } from "vue";
import { BACKEND_URL } from "@/config";
import axios from "axios";
import type UserComment from "@/api/Resources/Types/UserComment";
import { CursorPaginatedResourceCollection } from "@/api/CursorPaginatedResourceCollection";
import { parseUserComment } from "@/api/Resources/Types/UserComment";

export function useUserComments() {
  const userComments = reactive(new CursorPaginatedResourceCollection<UserComment>());

  const isLoading = ref(false);
  const loadedUserId = ref<null | number>(null);

  // prettier-ignore
  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  const pushUserComments = function (responseData: any) {
    /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
    const itemsToPush: UserComment[] = (responseData.data as any[]).map(
      (userComment) => parseUserComment(userComment)
    );

    userComments.pushCursor(itemsToPush, responseData.links.next);
  };

  const fetchUserCommentsPage = function (userId: number) {
    if (!userId) return;

    isLoading.value = true;
    loadedUserId.value = userId;

    let url = `${BACKEND_URL}/api/users/${userId}/comments`;

    if (userComments.cursorWasPushedAtLeastOnce()) {
      if (!userComments.doesCursorHaveMore()) return;
      // use cursor link from collection instead
      url = userComments.getNextCursor() as string;
    }

    axios.get(url).then(({ data }) => {
      if (userId == loadedUserId.value) {
        pushUserComments(data);
        isLoading.value = false;
      }
    });
  };

  const fetchNextPage = function () {
    if (!(null === loadedUserId.value)) {
      fetchUserCommentsPage(loadedUserId.value);
    }
  };

  const fetchFirstPage = function (userId: number) {
    userComments.clear();
    fetchUserCommentsPage(userId);
  };

  const refetchUserComments = function () {
    if (loadedUserId.value) {
      userComments.clear();
      fetchFirstPage(loadedUserId.value);
    }
  };

  return {
    userComments,
    fetchFirstPage,
    fetchNextPage,
    refetchUserComments,
    loadedUserId: readonly(loadedUserId),
    isLoading,
  };
}
