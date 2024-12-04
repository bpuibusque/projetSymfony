<template>
  <div class="messages-container">
    <h1 class="title">Messagerie Privée</h1>
    <button @click="goToNewMessage" class="new-message-btn">Nouveau Message</button>

    <div class="messages-section">
      <h2 class="section-title">Messages Reçus</h2>
      <ul v-if="receivedMessages.length" class="messages-list">
        <li v-for="message in receivedMessages" :key="message.id" class="message-card">
          <div class="message-header">
            <span class="message-sender">{{ message.sender }}</span>
            <small class="message-date">{{ new Date(message.createdAt).toLocaleString() }}</small>
          </div>
          <p class="message-content">{{ message.content }}</p>
        </li>
      </ul>
      <p v-else class="no-messages">Aucun message reçu.</p>
    </div>

    <div class="messages-section">
      <h2 class="section-title">Messages Envoyés</h2>
      <ul v-if="sentMessages.length" class="messages-list">
        <li v-for="message in sentMessages" :key="message.id" class="message-card">
          <div class="message-header">
            <span class="message-recipient">À {{ message.recipient }}</span>
            <small class="message-date">{{ new Date(message.createdAt).toLocaleString() }}</small>
          </div>
          <p class="message-content">{{ message.content }}</p>
        </li>
      </ul>
      <p v-else class="no-messages">Aucun message envoyé.</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'PrivateMessages',
  data() {
    return {
      receivedMessages: [],
      sentMessages: [],
    };
  },
  created() {
    this.fetchReceivedMessages();
    this.fetchSentMessages();
  },
  methods: {
    fetchReceivedMessages() {
      const user = JSON.parse(localStorage.getItem('user'));
      const userID = user ? user['id'] : null;
      if (!userID) {
        console.error('Aucun ID utilisateur trouvé dans le localStorage.');
        return;
      }
      axios
        .get('/private_message/received', { params: { userID } })
        .then((response) => {
          this.receivedMessages = response.data;
        })
        .catch((error) => {
          console.error('Erreur lors de la récupération des messages reçus :', error);
        });
    },
    fetchSentMessages() {
      const user = JSON.parse(localStorage.getItem('user'));
      const userID = user ? user['id'] : null;
      if (!userID) {
        console.error('Aucun ID utilisateur trouvé dans le localStorage.');
        return;
      }
      axios
        .get('/private_message/sent', { params: { userID } })
        .then((response) => {
          this.sentMessages = response.data;
        })
        .catch((error) => {
          console.error('Erreur lors de la récupération des messages envoyés :', error);
        });
    },
    goToNewMessage() {
      this.$router.push('/private_message/new');
    },
  },
};
</script>

<style>
.messages-container {
  max-width: 900px;
  margin: 20px auto;
  padding: 20px;
  background: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  font-family: Arial, sans-serif;
}

.title {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
  font-size: 28px;
  font-weight: bold;
}

.new-message-btn {
  display: block;
  width: fit-content;
  margin: 0 auto 20px;
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.new-message-btn:hover {
  background-color: #0056b3;
}

.messages-section {
  margin-bottom: 30px;
}

.section-title {
  font-size: 22px;
  color: #555;
  margin-bottom: 15px;
  border-bottom: 2px solid #ddd;
  padding-bottom: 5px;
}

.messages-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.message-card {
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  margin-bottom: 15px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.message-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.message-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.message-sender,
.message-recipient {
  font-weight: bold;
  color: #007bff;
}

.message-date {
  font-size: 12px;
  color: #888;
}

.message-content {
  font-size: 14px;
  color: #333;
  line-height: 1.5;
}

.no-messages {
  text-align: center;
  color: #888;
  font-size: 16px;
  font-style: italic;
}
</style>
