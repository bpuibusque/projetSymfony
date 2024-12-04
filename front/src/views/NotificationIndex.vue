<template>
  <div class="notification-container">
    <h1>Mes Notifications</h1>
    <button @click="goToCreateNotification" class="create-notification-btn">Créer une Notification</button>
    <ul v-if="notifications.length" class="notification-list">
      <li
        v-for="notification in notifications"
        :key="notification.id"
        :class="{ 'notification-unread': !notification.isRead }"
        class="notification-item"
      >
        <p>{{ notification.message }}</p>
        <small>{{ new Date(notification.createdAt).toLocaleString() }}</small>
        <button @click="markAsRead(notification.id)">Marquer comme lu</button>
        <router-link
          v-if="notification.post"
          :to="`/posts/${notification.post}`"
          class="view-post-link"
        >
          Voir le post
        </router-link>
      </li>
    </ul>
    <p v-else>Aucune notification pour l'instant.</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'NotificationIndex',
  data() {
    return {
      notifications: [],
    };
  },
  created() {
    this.fetchNotifications();
  },
  methods: {
    fetchNotifications() {
      axios
        .get('/notification/api')
        .then((response) => {
          this.notifications = response.data;
        })
        .catch((error) => {
          console.error('Erreur lors de la récupération des notifications :', error);
        });
    },
    markAsRead(notificationId) {
      axios
        .post(`/notification/${notificationId}/mark-read`)
        .then(() => {
          const notification = this.notifications.find((n) => n.id === notificationId);
          if (notification) notification.isRead = true;
        })
        .catch((error) => {
          console.error('Erreur lors de la mise à jour de la notification :', error);
        });
    },
    goToCreateNotification() {
      this.$router.push('/notifications/create');
    },
  },
};
</script>

<style>
.notification-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}

.notification-list {
  list-style: none;
  padding: 0;
}

.notification-item {
  background: #f9f9f9;
  border: 1px solid #ddd;
  padding: 15px;
  margin-bottom: 10px;
  border-radius: 5px;
}

.notification-unread {
  background: #eef;
}

.view-post-link {
  display: inline-block;
  margin-top: 10px;
  color: #007bff;
  text-decoration: none;
}

.view-post-link:hover {
  text-decoration: underline;
}

button {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 10px;
}

button:hover {
  background-color: #218838;
}

/* Style for Create Notification Button */
.create-notification-btn {
  display: block;
  margin: 10px auto 20px;
  padding: 10px 15px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  text-align: center;
}

.create-notification-btn:hover {
  background-color: #0056b3;
}
</style>
