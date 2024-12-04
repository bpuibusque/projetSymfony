<template>
  <div class="notification-creator-container">
    <h1>Créer une Notification</h1>
    <form @submit.prevent="createNotification">
      <div class="form-group">
        <label for="post">Sélectionner un post :</label>
        <select v-model="selectedPost" id="post" required>
          <option value="" disabled>Choisir un post</option>
          <option v-for="post in posts" :key="post.id" :value="post.id">
            {{ post.title }}
          </option>
        </select>
      </div>
      <button type="submit" class="submit-button">Envoyer</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CreateNotification',
  data() {
    return {
      posts: [], // Liste des posts récupérés
      selectedPost: '', // Post sélectionné
    };
  },
  created() {
    this.fetchPosts(); // Charger les posts lors de la création du composant
  },
  methods: {
    // Méthode pour récupérer tous les posts depuis l'API
    fetchPosts() {
      axios
        .get('posts/api/posts') // Changez cette URL selon votre route backend
        .then((response) => {
          this.posts = response.data;
        })
        .catch((error) => {
          console.error('Erreur lors de la récupération des posts :', error);
        });
    },
    // Méthode pour créer une notification
    createNotification() {
      const user = JSON.parse(localStorage.getItem('user')); // Récupérer les infos utilisateur
      const userID = user ? user['id'] : null;

      if (!userID) {
        alert('Utilisateur non trouvé.');
        return;
      }

      if (!this.selectedPost) {
        alert('Veuillez sélectionner un post.');
        return;
      }

      axios
        .post('/notification/api/create', {
          postID: this.selectedPost, // ID du post sélectionné
          userID, // ID de l'utilisateur
        })
        .then(() => {
          alert('Notification créée avec succès.');
          this.selectedPost = ''; // Réinitialiser la sélection
        })
        .catch((error) => {
          console.error('Erreur lors de la création de la notification :', error);
        });
    },
  },
};
</script>

<style>
.notification-creator-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

select {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: #fff;
}

.submit-button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 4px;
  cursor: pointer;
}

.submit-button:hover {
  background-color: #0056b3;
}
</style>
