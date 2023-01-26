import _ from "lodash";

export const getCookie = function (name: string) {
  const matches = document.cookie.match(
    new RegExp(
      /* eslint-disable-next-line */
      "(^|; )" + _.escapeRegExp(name) + "=([^;]*)"
    )
  );
  return matches ? decodeURIComponent(matches[1].replace(new RegExp('^; '), '')) : undefined;
};

export const cookieIsPresent = function (name: string) {
  return !_.isUndefined(getCookie(name));
};
