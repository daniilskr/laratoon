export class PaginatedResourceCollection<T> {
  items: T[];

  pageNumberWindowStart: number;
  pageNumberWindowEnd: number;
  totalPages: number;
  totalItems: number;
  perPage: number;

  isBroken: boolean;
  somethingWasRemoved: boolean;

  constructor() {
    this.items = [];
    this.isBroken = false;
    this.somethingWasRemoved = false;
    this.pageNumberWindowStart = 0;
    this.pageNumberWindowEnd = 0;
    this.totalPages = 0;
    this.totalItems = 0;
    this.perPage = 0;
  }

  nextPageNumber() {
    return this.pageNumberWindowEnd < this.totalPages ? this.pageNumberWindowEnd + 1 : null;
  }

  previousPageNumber() {
    return this.pageNumberWindowStart > 1 ? this.pageNumberWindowStart - 1 : null;
  }

  updatePagesInfo(page: Page<T>) {
    if (this.totalPages !== page.totalPages) {
      this.totalPages = page.totalPages;
    }

    if (this.totalItems !== page.totalItems) {
      this.totalItems = page.totalPages;
      this.somethingWasRemoved = true;
    }

    if (this.perPage !== page.perPage) {
      this.isBroken = true;
    }
  }

  addPageBeforeWindow(page: PageBeforeWindow<T>) {
    if (page.pageNumber !== this.previousPageNumber()) {
      this.isBroken = true;
    }

    this.updatePagesInfo(page);
    this.pageNumberWindowStart = page.pageNumber;
    this.items.unshift(...page.items);

    return !this.isBroken;
  }

  addPageAfterWindow(page: PageAfterWindow<T>) {
    if (page.pageNumber !== this.nextPageNumber()) {
      this.isBroken = true;
    }

    this.updatePagesInfo(page);
    this.pageNumberWindowEnd = page.pageNumber;
    this.items.push(...page.items);

    return !this.isBroken;
  }
}

export interface Page<T> {
  items: T[];
  pageNumber: number;
  totalPages: number;
  totalItems: number;
  perPage: number;
}

export interface PageAfterWindow<T> extends Page<T> {
  itemsAfterPage: number;
}

export interface PageBeforeWindow<T> extends Page<T> {
  itemsBeforePage: number;
}
