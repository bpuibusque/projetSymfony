<template>
  <div class="notification-creator-container">
    <h1>Créer une Notification</h1>
    <form @submit.prevent="createNotification">
      <div class="form-group">
        <label for="message">Message :</label>
        <textarea v-model="message" id="message" rows="4" required></textarea>
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
      message: '',
    };
  },
  methods: {
    createNotification() {
      axios
        .post('/notification/api/create', { message: this.message })
        .then(() => {
          alert('Notification créée avec succès.');
          this.message = ''; // Réinitialiser le champ
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

textarea {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 4px;
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
