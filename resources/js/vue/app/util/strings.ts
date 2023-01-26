export const pluralize = function (number: number, singular: string, plural: string) {
  return number === 1 ? singular : plural;
};

export const slugify = (s: string) => s.replace(/\s/g, "-");

export const wordsToUpper = (s: string) => s.replace(/(^| )(\w)/g, (m: string) => m.toUpperCase());

export const pluralizeWithNumber = function (number: number, singular: string, plural: string) {
  return number + " " + pluralize(number, singular, plural);
};
