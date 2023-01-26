import type ComicUserList from "./ComicUserList";
import { parseComicUserList } from "./ComicUserList";

export default interface CurrentUser {
  id: number;
  email: string;
  fullName: string;
  avatar: {
    medium: string;
  };
  comicUserLists: ComicUserList[];
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseCurrentUser = (currentUser: any) => ({
  id: currentUser.id,
  email: currentUser.email,
  fullName: currentUser.fullName,

  avatar: {
    medium: currentUser.avatar.medium,
  },

  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  comicUserLists: (currentUser.comicUserLists as any[]).map((list) => parseComicUserList(list)),
});
