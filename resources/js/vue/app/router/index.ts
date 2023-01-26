import { createRouter, createWebHistory } from "vue-router";
// import Home from "../views/Home.vue";
import Catalog from "../views/Catalog.vue";
import Viewer from "../views/Viewer.vue";
import Comic from "../views/Comic.vue";
import User from "../views/User.vue";
import UserList from "../views/User/UserList.vue";
import UserComments from "../views/User/UserComments.vue";
import UserNotifications from "../views/User/UserNotifications.vue";
import PageNotFound from "../views/PageNotFound.vue";

const routes = [
  // {
  //   path: "/",
  //   name: "home.",
  //   component: Home,
  // },
  {
    path: "/read/:comic/:episode",
    name: "viewer.",
    component: Viewer,
  },
  {
    path: "/comic/:comic",
    name: "comic.",
    component: Comic,
  },
  {
    path: "/catalog",
    alias: "/",
    name: "catalog.",
    component: Catalog,
  },
  {
    path: "/user/:user",
    name: "user.",
    component: User,
    children: [
      {
        path: "lists/:list?",
        name: "user.lists.",
        component: UserList,
      },
      {
        path: "comments",
        name: "user.comments.",
        component: UserComments,
      },
      {
        path: "notifications",
        name: "user.notifications.",
        component: UserNotifications,
      },
    ],
  },
  {
    path: "/:pathMatch(.*)*",
    name: "page-not-found.",
    component: PageNotFound,
  },
  // {
  //   path: '/about',
  //   name: 'About',
  //   // route level code-splitting
  //   // this generates a separate chunk (about.[hash].js) for this route
  //   // which is lazy-loaded when the route is visited.
  //   component: () => import('../views/About.vue')
  // }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { left: 0, top: 0 };
    }
  },
});

export default router;
