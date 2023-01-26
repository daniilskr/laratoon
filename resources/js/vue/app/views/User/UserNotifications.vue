<template>
  <BaseUserNestedView class="user-notifications">
    <template v-slot:navbar>
      <h1 class="navbar-page-title">Notifications</h1>
    </template>
    <template v-slot:content>
      <div class="user-notifications__content padding-x-75 padding-t-25">
        <div class="padding-x-10 padding-b-10 padding-t-15">
          <div
            v-for="notification in notifications.items"
            :key="notification.id"
            class="user-notifications__item-wrapper padding-t-25 padding-t-0first"
          >
            <div class="padding-b-15">
              <UserNotification
                :title="notification.title"
                :text="notification.text"
                :published-time="notification.publishedAt"
                :img="notification.img"
              />
            </div>
          </div>
        </div>
      </div>
    </template>
  </BaseUserNestedView>
</template>

<script setup lang="ts">
import { reactive } from "vue";
import UserNotification from "@/components/User/UserNotification.vue";
import BaseUserNestedView from "@/views/User/BaseUserNestedView.vue";

const notifications = reactive({
  items: [
    {
      id: 1,
      title: "New episode #281",
      publishedAt: "8 hours ago",
      text: "in Pepper and Carrot",
      img: "#FA9952",
    },
    {
      id: 2,
      title: "New episode #280",
      publishedAt: "1 year ago",
      text: "in Pepper and Carrot",
      img: "#FA9952",
    },
    {
      id: 3,
      title: "New episode #279",
      publishedAt: "8 hours ago",
      text: "in Pepper and Carrot",
      img: "#F9E74B",
    },
    {
      id: 4,
      title: "New episode #278",
      publishedAt: "2 months ago",
      text: "in Pepper and Carrot",
      img: "#FA9952",
    },
    {
      id: 5,
      title: "New episode #277",
      publishedAt: "1 year ago",
      text: "in Pepper and Carrot",
      img: "#FA9952",
    },
    {
      id: 6,
      title: "New episode #276",
      publishedAt: "8 hours ago",
      text: "in Pepper and Carrot",
      img: "#F9E74B",
    },
    {
      id: 7,
      title: "New episode #275",
      publishedAt: "1 year ago",
      text: "in Pepper and Carrot",
      img: "#FA9952",
    },
  ],
});
</script>

<style scoped>
.user-notifications {
  position: relative;
}

.user-notifications__item-wrapper {
  border-bottom: 1px solid #f5f5f5;
}

.user-notifications__item-wrapper:last-child {
  border-bottom: none;
}

.user-notifications__navbar__list-link__text {
  padding-left: 7px;
}

.user-notifications__navbar {
  position: fixed;
  background: white;
  display: flex;
  border-bottom: 1px solid #f5f5f5;
}

.user-notifications__navbar__list-link {
  margin-right: 30px;
  display: flex;
  align-items: baseline;
}

.user-notifications__navbar__list-link:last-child {
  margin-right: 0;
}
</style>
