export default interface ComicCard {
  id: number;
  slug: string;
  title: string;
  description: string;
  author: {
    id: number;
    fullName: string;
  };
  comicPoster: {
    medium: string;
  };

  statistics: {
    likes: {
      total: number;
    };
  };
}

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
export const parseComicCard = (comicCard: any): ComicCard => ({
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
});
