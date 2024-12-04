<template>
  <div class="new-post-container">
    <h1>Créer un nouveau post</h1>
    <form @submit.prevent="createPost">
      <div class="form-group">
        <label for="title">Titre :</label>
        <input v-model="title" id="title" type="text" required />
      </div>
      <div class="form-group">
        <label for="content">Contenu :</label>
        <textarea v-model="content" id="content" rows="5" required></textarea>
      </div>
      <button type="submit" class="create-button">Publier</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'NewPost',
  data() {
    return {
      title: '',
      content: '',
    };
  },
  methods: {
    createPost() {
      const url = `/posts/api/new`; // Nouvelle URL pour l'API
      const payload = {
        title: this.title,
        content: this.content,
        commission: { id: this.$route.params.id }, // Inclure l'ID de la commission
      };

      axios
        .post(url, payload)
        .then(() => {
          this.$router.push(`/commissions/${this.$route.params.id}/posts`);
        })
        .catch((error) => {
          console.error('Erreur lors de la création du post:', error);
        });
    }
  },
};
</script>

<style>
.new-post-container {
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

input,
textarea {
  width: 100%;
  padding: 8px;
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.create-button {
  background-color: #007bff;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.create-button:hover {
  background-color: #0056b3;
}
</style>
