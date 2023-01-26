import { ref } from "vue";
import { BACKEND_URL } from "@/config";
import axios from "axios";
import type UserProfileMainInfo from "@/api/Resources/Types/UserProfileMainInfo";
import { parseUserProfileMainInfo } from "@/api/Resources/Types/UserProfileMainInfo";

export function useUserProfileMainInfo() {
  const userProfileMainInfo = ref<null | UserProfileMainInfo>(null);

  /* eslint-disable-next-line @typescript-eslint/no-explicit-any */
  const setUserProfileMainInfo = (responseData: any) => {
    userProfileMainInfo.value = parseUserProfileMainInfo(responseData.data);
  };

  const isLoading = ref(false);
  let userToLoad: null | number = null;

  const fetchUserProfileMainInfo = (user: number) => {
    if (isLoading.value && userToLoad === user) {
      return;
    }
    isLoading.value = true;
    userToLoad = user;

    axios
      .get(`${BACKEND_URL}/api/users/${user}/profile-main-info`)
      .then(({ data }) => {
        if (userToLoad === user) {
          setUserProfileMainInfo(data);
        }
      })
      .catch((err) => {
        console.log(err);
      })
      .then(() => {
        if (userToLoad === user) {
          isLoading.value = false;
        }
      });
  };

  return {
    isLoading,
    userProfileMainInfo,
    fetchUserProfileMainInfo,
  };
}
