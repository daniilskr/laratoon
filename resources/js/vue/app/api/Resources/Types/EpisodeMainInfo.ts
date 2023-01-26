export default interface EpisodeMainInfo {
  id: number;
  title: string;
  number: number;

  comic: {
    id: number;
    title: string;
    slug: string;
  };

  pages: {
    id: number;
    order: number;
    image: {
      medium: string;
    };
  }[];

  commentable: {
    id: number;
  };
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseEpisodeMainInfo = (info: any): EpisodeMainInfo => ({
  id: info.id,
  title: info.title,
  number: info.number,

  comic: {
    id: info.comic.id,
    title: info.comic.title,
    slug: info.comic.slug,
  },

  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  pages: info.pages.map((page: any) => ({
    id: page.id,
    order: page.order,
    image: {
      medium: page.image.medium,
    },
  })),

  commentable: {
    id: info.commentable.id,
  },
});
