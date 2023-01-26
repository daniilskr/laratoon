import type ComicUserList from "./ComicUserList";

export default interface UserProfileMainInfo {
  id: number;
  name: string;

  comicUserLists: ComicUserList[];

  avatar: {
    medium: string;
  };

  statistics: {
    likes: number;
    comments: number;
    views: number;
    stars: number;
  };
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseUserProfileMainInfo = (info: any) => ({
  id: info.id,
  name: info.name,

  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  comicUserLists: (info.comicUserLists as any[]).map((l) => ({
    id: l.id,
    color: l.color,
    name: l.name,
    slug: l.slug,
  })),

  avatar: {
    medium: info.avatar.medium,
  },

  statistics: {
    comments: info.comments,
    likes: info.likes,
    views: info.views,
    stars: info.stars,
  },
});
