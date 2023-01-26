import axios from "axios";
import _ from "lodash";
import { ref, reactive, readonly } from "vue";
import { BACKEND_URL } from "@/config";
import type Comment from "@/api/Resources/Types/Comment";
import { CursorPaginatedResourceCollection } from "@/api/CursorPaginatedResourceCollection";
import { parseComment } from "@/api/Resources/Types/Comment";

export const useReplies = function () {
  const replies = reactive(new CursorPaginatedResourceCollection<Comment>());

  const isLoading = ref(false);
  const loadedRoot = ref<null | number>(null);

  // prettier-ignore
  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  const pushReplies = function (responseData: any) {
    /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
    const itemsToPush: Comment[] = (responseData.data as any[]).map(
      (comment) => parseComment(comment)
    );

    replies.pushCursor(itemsToPush, responseData.links.next);
  };

  const fetchRepliesPage = function (rootCommentId: number) {
    if (!rootCommentId) return;

    isLoading.value = true;
    loadedRoot.value = rootCommentId;

    let url = `${BACKEND_URL}/api/comment-replies-with-root/${rootCommentId}`;

    if (replies.cursorWasPushedAtLeastOnce()) {
      if (!replies.doesCursorHaveMore()) return;
      // use cursor link from collection instead
      url = replies.getNextCursor() as string;
    }

    axios.get(url).then(({ data }) => {
      pushReplies(data);
      isLoading.value = false;
    });
  };

  const fetchNextPage = function () {
    if (!_.isNull(loadedRoot.value)) {
      fetchRepliesPage(loadedRoot.value);
    }
  };

  const fetchFirstPage = function (rootCommentId: number) {
    replies.clear();
    fetchRepliesPage(rootCommentId);
  };

  const refetchReplies = function () {
    if (loadedRoot.value) {
      replies.clear();
      fetchFirstPage(loadedRoot.value);
    }
  };

  const onNewUserPostedReply = function (reply: Comment) {
    if (!replies.cursorWasPushedAtLeastOnce()) {
      // If replies were not loaded yet,
      // newly created user reply will be
      // with other auto-loaded comments
      return;
    }

    // TODO: (MAYBE?) We could also load new replies
    // that were added by other users in
    // between user-posted comment and last replies load

    // Manually insert newly posted user reply into collection
    if (!replies.items.find((r) => r.id === reply.id)) {
      replies.items.unshift(reply);
    }
  };

  return {
    replies,
    fetchFirstPage,
    fetchNextPage,
    refetchReplies,
    loadedRoot: readonly(loadedRoot),
    isLoading,
    onNewUserPostedReply,
  };
};
