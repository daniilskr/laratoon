import axios from "axios";
import _ from "lodash";
import { ref, reactive, readonly } from "vue";
import { BACKEND_URL } from "@/config";
import type Comment from "@/api/Resources/Types/Comment";
import { CursorPaginatedResourceCollection } from "@/api/CursorPaginatedResourceCollection";
import { parseComment } from "@/api/Resources/Types/Comment";

export const useCommentable = function () {
  const comments = reactive(new CursorPaginatedResourceCollection<Comment>());

  const isLoading = ref(false);
  const loadedCommentable = ref<null | number>(null);

  // prettier-ignore
  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  const pushComments = function (responseData: any) {
    /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
    const itemsToPush: Comment[] = (responseData.data as any[]).map( 
      (comment) => parseComment(comment)
    ); 

    comments.pushCursor(itemsToPush, responseData.links.next);
  };

  const fetchCommentsPage = function (commentableId: number) {
    if (!commentableId) return;

    isLoading.value = true;
    loadedCommentable.value = commentableId;

    let url = `${BACKEND_URL}/api/root-comments-of-commentable/${commentableId}`;

    if (comments.cursorWasPushedAtLeastOnce()) {
      if (!comments.doesCursorHaveMore()) return;
      // use cursor link from collection instead
      url = comments.getNextCursor() as string;
    }

    axios.get(url).then(({ data }) => {
      pushComments(data);
      isLoading.value = false;
    });
  };

  const fetchNextPage = function () {
    if (!_.isNull(loadedCommentable.value)) {
      fetchCommentsPage(loadedCommentable.value);
    }
  };

  const fetchFirstPage = function (commentableId: number) {
    comments.clear();
    fetchCommentsPage(commentableId);
  };

  const refetchComments = function () {
    if (loadedCommentable.value) {
      comments.clear();
      fetchCommentsPage(loadedCommentable.value);
    }
  };

  const onNewUserPostedComment = function (comment: Comment) {
    comments.items.unshift(comment);
  };

  const incrementRootChildCommentsCachedCount = function (commentId: number) {
    comments.items.forEach((comment) => {
      if (comment.id === commentId) {
        comment.rootChildCommentsCachedCount++;
      }
    });
  };

  return {
    comments,
    fetchFirstPage,
    fetchNextPage,
    refetchComments,
    loadedCommentable: readonly(loadedCommentable),
    isLoading,
    onNewUserPostedComment,
    incrementRootChildCommentsCachedCount,
  };
};
