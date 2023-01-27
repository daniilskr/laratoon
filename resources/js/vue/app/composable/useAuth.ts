import { ref, computed } from "vue";
import axios from "axios";
import _ from "lodash";
import { BACKEND_URL } from "@/config";
import type CurrentUser from "@/api/Resources/Types/CurrentUser";
import { parseCurrentUser } from "@/api/Resources/Types/CurrentUser";

const currentUser = ref<null | CurrentUser>(null);

export const useAuth = function () {
  const isAuthenticated = computed(() => !_.isNull(currentUser.value));

  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  const setCurrentUser = function (user: null | any) {
    if (_.isNull(user)) {
      currentUser.value = null;
      return;
    }

    currentUser.value = parseCurrentUser(user);
  };

  const fetchCurrentUser = async function () {
    try {
      const response = await axios.get(`${BACKEND_URL}/api/current-user`);
      setCurrentUser(response.data.data);
      /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
    } catch (err: any) {
      if (err.response && err.response.status === 401) {
        setCurrentUser(null); // Unauthenticated
      } else {
        console.error(`failed to fetch user: ${err}`);
      }
    }
  };

  return {
    currentUser,
    isAuthenticated,
    fetchCurrentUser,
  };
};
