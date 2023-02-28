import type ComicCard from "@/api/Resources/Types/ComicCard";
import { parseComicCard } from "@/api/Resources/Types/ComicCard";

export enum ComicCardSectionType {
  SmallWideCards = "small_wide_cards",
  GoToMore = "go_to_more",
  Heading = "heading",
}

export default interface ComicCardSection {
  title: string;
  type: ComicCardSectionType;
  sectionPoster: {
    medium: string;
  };
  comicCards: ComicCard[];
}

export const parseComicCardSection = (comicSection: any): ComicCardSection => ({
  title: comicSection.title,
  type: comicSection.type,
  sectionPoster: {
    medium: comicSection.sectionPoster.medium,
  },
  comicCards: (comicSection.comicCards as any[]).map((comicCard) => parseComicCard(comicCard as any)),
});
