import _ from "lodash";

export const formatDateLongAgo = function (pubDate: Date | string) {
  if (_.isString(pubDate)) pubDate = new Date(pubDate);
  const now = new Date();

  const diffSeconds = Math.floor((Number(now) - Number(pubDate)) / 1000);
  if (diffSeconds < 1) return "Now";
  if (diffSeconds === 1) return "1 second ago";
  if (diffSeconds < 60) return `${diffSeconds} seconds ago`;

  const diffMinutes = Math.floor(diffSeconds / 60);
  if (diffMinutes === 1) return "1 minute ago";
  if (diffMinutes < 60) return `${diffMinutes} minutes ago`;

  const diffHours = Math.floor(diffMinutes / 60);
  if (diffHours === 1) return "1 hour ago";
  if (diffHours < 24) return `${diffHours} hours ago`;

  const diffDays = Math.floor(diffHours / 24);
  if (diffDays === 1) return "1 day ago";
  if (diffDays < 29) return `${diffDays} days ago`;

  const diffMonths = Math.floor(diffDays / 29);
  if (diffMonths === 1) return "1 month ago";
  if (diffMonths < 12) return `${diffMonths} months ago`;

  const diffYears = Math.floor(diffMonths / 12);
  if (diffYears === 1) return "1 year ago";
  return `${diffYears} years ago`;
};
