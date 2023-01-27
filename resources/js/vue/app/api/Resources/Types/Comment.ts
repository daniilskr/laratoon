export default interface Comment {
  id: number;
  publishedAt: string;
  text: string;
  rootChildCommentsCachedCount: number;

  author: {
    id: number;
    fullName: string;
    avatar: {
      medium: string;
    };
  };

  likeable: {
    id: number;
    likesCachedCount: number;
    isLikedByUser: boolean;
  };

  commentable: {
    id: number;
  };

  rootCommentId: number;
  parentCommentId: number;
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseComment = (comment: any): Comment => ({
  id: comment.id,
  publishedAt: comment.publishedAt,
  text: comment.text,

  author: {
    id: comment.author.id,
    fullName: comment.author.fullName,
    avatar: {
      medium: comment.author.avatar.medium,
    },
  },

  likeable: {
    id: comment.likeable.id,
    likesCachedCount: comment.likeable.likesCachedCount,
    isLikedByUser: comment.likeable.isLikedByUser,
  },

  commentable: {
    id: comment.commentable.id,
  },

  rootChildCommentsCachedCount: comment.rootChildCommentsCachedCount ?? 0,
  rootCommentId: comment.rootCommentId ?? null,
  parentCommentId: comment.parentCommentId ?? null,
});
