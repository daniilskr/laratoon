function makeCursor() {
  return {
    next: null,
    wasPushedAtLeastOnce: false,
    timesFetched: 0,
  };
}

export class CursorPaginatedResourceCollection<T> {
  items: T[];
  _cursor: {
    next: null | string;
    wasPushedAtLeastOnce: boolean;
    timesFetched: number;
  };

  constructor() {
    this.items = [];
    this._cursor = makeCursor();
  }

  clear() {
    this.items = [];
    this._cursor = makeCursor();
  }

  getNextCursor() {
    return this._cursor.next;
  }

  pushCursor(items: T[], cursorNext: string | null) {
    this._cursor.next = cursorNext;

    if (!this._cursor.wasPushedAtLeastOnce) {
      this._cursor.wasPushedAtLeastOnce = true;
    }

    this.items.push(...items);
    this._cursor.timesFetched++;
  }

  isCursorCompletelyFetched() {
    return this.cursorWasPushedAtLeastOnce() && !this.doesCursorHaveMore();
  }

  doesCursorHaveMore() {
    return !(null === this._cursor.next);
  }

  cursorWasPushedAtLeastOnce() {
    return this._cursor.wasPushedAtLeastOnce;
  }

  timesFetched() {
    return this._cursor.timesFetched;
  }
}
