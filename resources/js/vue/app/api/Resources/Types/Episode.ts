export default interface Episode {
  id: number;
  title: string;
  number: number;
  publishedAt: string;
  poster: {
    medium: string;
  };
  viewable: null | {
    viewsCachedCount: number;
    isSeenByUser: boolean;
  };
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseEpisode = (episode: any): Episode => ({
  id: episode.id,
  title: episode.title,
  number: episode.number,
  publishedAt: episode.publishedAt,

  poster: {
    medium: episode.poster.medium,
  },

  viewable: {
    viewsCachedCount: episode.viewsCachedCount,
    isSeenByUser: episode.isSeenByUser,
  },
});
