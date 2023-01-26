import type ComicCard from "@/api/Resources/Types/ComicCard";

export default interface ComicMainInfo {
  id: number;
  title: string;
  description: string;
  slug: string;

  author: {
    id: number;
    fullName: string;
  };

  cachedLatestViewedEpisode: null | {
    id: number;
    title: string;
    number: string;
  };

  comicPoster: {
    medium: string;
  };
  comicHeaderBackground: {
    medium: string;
  };

  statistics: {
    likes: {
      total: number;
    };
    comments: {
      total: number;
    };
    views: {
      total: number;
    };
  };

  commentable: {
    id: number;
  };

  comicUserListSlug: null | string;

  episodes: {
    id: number;
    title: string;
    publishedAt: string;
    number: number | string;
    poster: {
      medium: string;
    };
    viewable: {
      viewsCachedCount: number;
      isSeenByUser: boolean;
    };
  }[];

  tags: {
    id: number;
    name: string;
  }[];

  mainCharacters: {
    id: number;
    description: string;
    character: {
      id: number;
      fullName: string;
      poster: {
        medium: string;
      };
    };
  }[];

  otherComicsByAuthor: ComicCard[];
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseComicMainInfo = (comic: any): ComicMainInfo => ({
  id: comic.id,
  title: comic.title,
  description: comic.description,
  slug: comic.slug,

  cachedLatestViewedEpisode: comic.cachedLatestViewedEpisode
    ? {
        id: comic.cachedLatestViewedEpisode.id,
        title: comic.cachedLatestViewedEpisode.title,
        number: comic.cachedLatestViewedEpisode.number,
      }
    : null,

  comicPoster: {
    medium: comic.comicPoster.medium,
  },

  comicHeaderBackground: {
    medium: comic.comicHeaderBackground.medium,
  },

  author: {
    id: comic.author.id,
    fullName: comic.author.fullName,
  },

  comicUserListSlug: comic.comicUserListSlug,

  commentable: {
    id: comic.commentable.id,
  },

  statistics: {
    likes: {
      total: comic.statistics.likes.total,
    },
    views: {
      total: comic.statistics.views.total,
    },
    comments: {
      total: comic.statistics.comments.total,
    },
  },

  // prettier-ignore
  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  episodes: (comic.episodes as any[]).map(
    (ep) => ({
      id: ep.id,
      title: ep.title,
      number: ep.number,
      publishedAt: ep.publishedAt,
      poster: {
        medium: ep.poster.medium,
      },
      viewable: {
        id: ep.viewable.id,
        viewsCachedCount: ep.viewable.viewsCachedCount,
        isSeenByUser: ep.viewable.isSeenByUser,
      },
    })
  ),

  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  mainCharacters: (comic.mainCharacters as any[]).map((characterRole) => ({
    id: characterRole.id,
    description: characterRole.description,
    character: {
      id: characterRole.character.id,
      fullName: characterRole.character.fullName,
      poster: {
        medium: characterRole.character.poster.medium,
      },
    },
  })),

  // prettier-ignore
  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  tags: (comic.tags as any[]).map( 
    (tag) => ({
      id: tag.id,
      name: tag.name,
    })
  ),

  // prettier-ignore
  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  otherComicsByAuthor: (comic.otherComicsByAuthor as any[]).map( 
    (comicCard) => ({
      id: comicCard.id,
      slug: comicCard.slug,
      title: comicCard.title,
      description: comicCard.description,
      comicPoster: {
        medium: comicCard.comicPoster.medium,
      },
      author: {
        id: comicCard.author.id,
        fullName: comicCard.author.fullName,
      },
      statistics: {
        likes: {
          total: comicCard.statistics.likes.total,
        },
      },
    })
  ),
});
