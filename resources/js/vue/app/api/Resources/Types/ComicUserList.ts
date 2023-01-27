export default interface ComicUserList {
  id: number;
  name: string;
  slug: string;
  color: string;
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseComicUserList = (comicUserList: any): ComicUserList => ({
  id: comicUserList.id,
  name: comicUserList.name,
  color: comicUserList.color,
  slug: comicUserList.slug,
});
