import axios from "axios";
import { useAuth } from "@/composable/useAuth";
import { cookieIsPresent } from "@/util/cookie";
import { BACKEND_URL } from "@/config";

const { isAuthenticated, fetchCurrentUser } = useAuth();

/* eslint-disable-next-line @typescript-eslint/no-explicit-any */
const unauthenticatedErrorHandler = async function (err: any) {
  if (!isAuthenticated.value) {
    console.warn("Unauthenticated");
  } else {
    console.error(err);
  }
};

const CSRF_COOKIE_NAME = "XSRF-TOKEN";

const getCsrfCookie = async function () {
  console.log("fetching csrf token");
  await axios.get(`${BACKEND_URL}/sanctum/csrf-cookie`);
};

const ensureCsrfCookieAcquired = async function () {
  if (!cookieIsPresent(CSRF_COOKIE_NAME)) {
    await getCsrfCookie();
  }
};

export const bootSecurityService = async function () {
  const promises = [fetchCurrentUser(), ensureCsrfCookieAcquired()];

  await Promise.all(promises);

  axios.interceptors.response.use(
    (r) => r,
    (e) => {
      if (e.response && e.response.status === 401) unauthenticatedErrorHandler(e.response);
      return Promise.reject(e);
    }
  );
};
