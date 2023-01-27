export default interface Comment {
  id: number;
  publishedAt: string;
  text: string;
  rootChildCommentsCachedCount: number;

  commentable: {
    id: number;
    owner: {
      id: number;
      label: string;
    };
  };

  likeable: {
    id: number;
    likesCachedCount: number;
  };
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseUserComment = (userComment: any) => ({
  id: userComment.id,
  publishedAt: userComment.publishedAt,
  text: userComment.text,
  rootChildCommentsCachedCount: userComment.rootChildCommentsCachedCount,

  commentable: {
    id: userComment.commentable.id,
    owner: {
      id: userComment.commentable.owner.id,
      label: userComment.commentable.owner.label,
    },
  },

  likeable: {
    id: userComment.likeable.id,
    likesCachedCount: userComment.likeable.likesCachedCount,
  },
});
