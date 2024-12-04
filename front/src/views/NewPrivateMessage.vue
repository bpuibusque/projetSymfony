<template>
    <div class="new-message-container">
      <h1>Envoyer un Message Privé</h1>
      <form @submit.prevent="sendMessage">
        <div class="form-group">
          <label for="recipient">Destinataire :</label>
          <select v-model="recipientId" id="recipient" required>
            <option v-for="user in users" :key="user.id" :value="user.id">
              {{ user.email }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <label for="content">Message :</label>
          <textarea id="content" v-model="content" rows="5" required></textarea>
        </div>
        <button type="submit" class="send-button">Envoyer</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'NewPrivateMessage',
    data() {
      return {
        users: [],
        recipientId: null,
        content: '',
      };
    },
    created() {
      this.fetchUsers();
            },
    methods: {
        fetchUsers() {
        const token = localStorage.getItem('token'); // Récupérer le token après login

        axios.get('/users/api/users', {
            headers: {
            Authorization: `Bearer ${token}`, // Ajouter le jeton d'authentification
            },
        })
            .then((response) => {
            this.users = response.data;
            })
            .catch((error) => {
            console.error('Erreur lors de la récupération des utilisateurs :', error);
            });
        },
      sendMessage() {
        const user = JSON.parse(localStorage.getItem('user'));
        const userID = user ? user['id'] : null;
        if (!userID) {
          console.error('Aucun ID utilisateur trouvé dans le localStorage.');
          return;
        }
        axios.post('/private_message/api/new', {
          recipientId: this.recipientId,
          content: this.content,
          userID  
        })
          .then(() => {
            this.$router.push('/private-messages');
          })
          .catch((error) => {
            console.error('Erreur lors de l\'envoi du message :', error);
          });
      },
    },
  };
  </script>
  
  <style>
  .new-message-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  textarea,
  select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  .send-button {
    background-color: #28a745;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .send-button:hover {
    background-color: #218838;
  }
  </style>
  