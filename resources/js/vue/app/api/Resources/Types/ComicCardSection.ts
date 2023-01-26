import type ComicCard from "@/api/Resources/Types/ComicCard";

export enum ComicCardSectionType {
  SmallWideCards = "small_wide_cards",
  GoToMore = "go_to_more",
}

export default interface ComicCardSection {
  title: string;
  type: ComicCardSectionType;
  comicCards: ComicCard[];
}
