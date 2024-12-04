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
        <!-- Affiche le bouton approprié en fonction de l'état d'abonnement -->
        <button
          v-if="!commission.isSubscribed"
          class="subscribe-button"
          @click="subscribeToCommission(commission.id)"
        >
          S'abonner
        </button>
        <button
          v-else
          class="unsubscribe-button"
          @click="unsubscribeFromCommission(commission.id)"
        >
          Se désabonner
        </button>
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
      commissions: [], // Liste des commissions
    };
  },
  created() {
    this.fetchCommissions(); // Charger les commissions au démarrage
  },
  methods: {
    async fetchCommissions() {
      const user = JSON.parse(localStorage.getItem('user')); // Récupère l'utilisateur connecté
      const userID = user ? user.id : null;
      try {
        const response = await axios.get('/commissions/api/getSubscribe', { params: { userID } });
        this.commissions = response.data; // Assigner les données reçues
      } catch (error) {
        console.error('Erreur lors de la récupération des commissions:', error);
      }
    },
    async subscribeToCommission(commissionId) {
      const user = JSON.parse(localStorage.getItem('user')); // Récupère l'utilisateur connecté
      const userID = user ? user.id : null;

      if (!userID) {
        alert('Utilisateur non connecté.');
        return;
      }

      try {
        // Envoi de la requête POST
        await axios.post(`/commissions/${commissionId}/subscribe`, { userID });

        // Met à jour localement l'état de la commission après l'abonnement
        const commission = this.commissions.find((c) => c.id === commissionId);
        if (commission) {
          commission.isSubscribed = true;
        }

        alert(`Vous êtes maintenant abonné à la commission ${commissionId}.`);
      } catch (error) {
        console.error("Erreur lors de l'abonnement à la commission :", error);
      }
    },
    async unsubscribeFromCommission(commissionId) {
      const user = JSON.parse(localStorage.getItem('user')); // Récupère l'utilisateur connecté
      const userID = user ? user.id : null;

      if (!userID) {
        alert('Utilisateur non connecté.');
        return;
      }

      try {
        // Envoi de la requête DELETE pour se désabonner
        await axios.delete(`/commissions/${commissionId}/unsubscribe`, { data: { userID } });

        // Met à jour localement l'état de la commission après le désabonnement
        const commission = this.commissions.find((c) => c.id === commissionId);
        if (commission) {
          commission.isSubscribed = false;
        }

        alert(`Vous êtes maintenant désabonné de la commission ${commissionId}.`);
      } catch (error) {
        console.error("Erreur lors du désabonnement à la commission :", error);
      }
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

.subscribe-button {
  display: inline-block;
  margin-top: 10px;
  background: #28a745;
  color: white;
  padding: 8px 15px;
  border-radius: 4px;
  font-size: 14px;
  text-align: center;
  cursor: pointer;
  transition: background 0.3s ease;
}

.subscribe-button:hover {
  background: #218838;
}

.unsubscribe-button {
  display: inline-block;
  margin-top: 10px;
  background: #dc3545;
  color: white;
  padding: 8px 15px;
  border-radius: 4px;
  font-size: 14px;
  text-align: center;
  cursor: pointer;
  transition: background 0.3s ease;
}

.unsubscribe-button:hover {
  background: #c82333;
}
</style>
