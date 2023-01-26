export default interface ComicUserListEntry {
  id: number;
  comic: {
    id: number;
    slug: string;
    title: string;
    episodesLeft: number;

    comicPoster: {
      medium: string;
    };

    cachedLatestViewedEpisode: null | {
      id: number;
      title: string;
      number: string;
    };

    author: {
      id: number;
      name: string;
    };
  };
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseComicUserListEntry = (entry: any): ComicUserListEntry => ({
  id: entry.id,
  comic: {
    id: entry.comic.id,
    slug: entry.comic.slug,
    title: entry.comic.title,
    episodesLeft: entry.comic.episodesLeft,

    comicPoster: {
      medium: entry.comic.comicPoster.medium,
    },

    cachedLatestViewedEpisode: entry.comic.cachedLatestViewedEpisode
      ? {
          id: entry.comic.cachedLatestViewedEpisode.id,
          title: entry.comic.cachedLatestViewedEpisode.title,
          number: entry.comic.cachedLatestViewedEpisode.number,
        }
      : null,

    author: {
      id: entry.comic.author.id,
      name: entry.comic.author.fullName,
    },
  },
});
