import { createApp } from "vue";
import { createPinia } from "pinia";
import { bootSecurityService } from "./providers/securityServiceProvider";
// @ts-expect-error JS file
import { detectOuterClick } from "./directives/detectOuterClick";

import axios from "axios";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.withCredentials = true;

import App from "./App.vue";
import router from "./router";

const app = createApp(App);

app.directive("detect-outer-click", detectOuterClick);
app.use(createPinia());
app.use(router);

const provideServices = async () => {
  await bootSecurityService();
};

provideServices().then(() => {
  app.mount("#app");
});
