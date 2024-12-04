<template>
  <div class="messages-container">
    <h1>Messagerie Privée</h1>
    <button @click="goToNewMessage" class="new-message-btn">Nouveau Message</button>
    <div>
      <h2>Messages reçus</h2>
      <ul v-if="receivedMessages.length">
        <li v-for="message in receivedMessages" :key="message.id">
          <strong>{{ message.sender }}</strong>: {{ message.content }}
          <small>{{ new Date(message.createdAt).toLocaleString() }}</small>
        </li>
      </ul>
      <p v-else>Aucun message reçu.</p>
    </div>
    <div>
      <h2>Messages envoyés</h2>
      <ul v-if="sentMessages.length">
        <li v-for="message in sentMessages" :key="message.id">
          À <strong>{{ message.recipient }}</strong>: {{ message.content }}
          <small>{{ new Date(message.createdAt).toLocaleString() }}</small>
        </li>
      </ul>
      <p v-else>Aucun message envoyé.</p>
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
          console.error('Erreur lors de la récupération des message reçus :', error);
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
          console.error('Erreur lors de la récupération des message envoyés :', error);
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
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.new-message-btn {
  background-color: #007bff;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  margin-bottom: 20px;
}

.new-message-btn:hover {
  background-color: #0056b3;
}

</style>
