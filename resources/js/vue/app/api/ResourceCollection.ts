export class ResourceCollection<T> {
  items: T[];

  constructor(items: T[] = []) {
    this.items = items;
  }
}
