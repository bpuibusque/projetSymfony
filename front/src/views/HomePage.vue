<template>
  <div v-if="user">
    <h1>Bienvenue, {{ user.email }}!</h1>
    <p>Ceci est la page d'accueil. Utilisez le menu de navigation pour explorer le site.</p>
    <button @click="logout" class="logout-btn">Logout</button>
  </div>
  <div v-else>
    <p>Redirecting to login...</p>
  </div>
</template>

<script>
export default {
  computed: {
    user() {
      // Récupérer l'utilisateur depuis localStorage
      return JSON.parse(localStorage.getItem('user'));
    },
  },
  methods: {
    logout() {
      // Effacer la session utilisateur côté frontend
      localStorage.removeItem('user');
      this.$router.push('/login'); // Rediriger vers la page de connexion
    },
  },
  created() {
    // Vérifier si l'utilisateur est connecté
    if (!this.user) {
      this.$router.push('/login'); // Rediriger vers /login si non connecté
    }
  },
};
</script>

<style>
.logout-btn {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  margin-top: 20px;
}

.logout-btn:hover {
  background-color: #c82333;
}
</style>
