<template>
  <div class="container">
    <h1 class="title">Liste des Commissions</h1>
    <div class="commissions-grid">
      <div v-for="commission in commissions" :key="commission.id" class="commission-card">
        <h2 class="commission-title">{{ commission.name }}</h2>
        <p class="commission-description">{{ commission.description }}</p>
        <router-link :to="`/commissions/${commission.id}/posts`" class="view-posts-button">
          Voir les posts
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CommissionPage',
  data() {
    return {
      commissions: [],
    };
  },
  created() {
    this.fetchCommissions();
  },
  methods: {
    fetchCommissions() {
      axios.get('/commissions/api')
        .then(response => {
          this.commissions = response.data;
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des commissions:', error);
        });
    },
  },
};
</script>

<style>
/* Container styling */
.container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}

/* Title styling */
.title {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
  font-size: 24px;
}

/* Grid layout for commissions */
.commissions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

/* Individual commission card styling */
.commission-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  background: #f9f9f9;
  transition: box-shadow 0.3s ease;
}

.commission-card:hover {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Title styling for each commission */
.commission-title {
  font-size: 18px;
  margin: 0 0 10px;
  color: #222;
}

/* Description styling */
.commission-description {
  font-size: 14px;
  color: #555;
  margin-bottom: 15px;
}

/* Button styling */
.view-posts-button {
  display: inline-block;
  text-decoration: none;
  background: #007bff;
  color: white;
  padding: 8px 15px;
  border-radius: 4px;
  font-size: 14px;
  text-align: center;
  transition: background 0.3s ease;
}

.view-posts-button:hover {
  background: #0056b3;
}
</style>
