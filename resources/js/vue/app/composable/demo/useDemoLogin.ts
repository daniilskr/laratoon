import { useAuth } from "@/composable/useAuth";
import { BACKEND_URL } from "@/config";
import axios from "axios";
const { fetchCurrentUser } = useAuth();

export const useDemoLogin = function () {
  const performDemoAuth = async function () {
    await axios.post(`${BACKEND_URL}/demo-login`);
  };

  const loginWithDemoCredentials = async function () {
    await performDemoAuth();
    await fetchCurrentUser();
  };

  return {
    loginWithDemoCredentials,
  };
};
