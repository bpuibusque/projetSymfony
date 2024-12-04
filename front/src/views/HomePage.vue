<template>
  <div>
    <h1>Welcome to our Application!</h1>
    <p>This is the homepage. Use the navigation menu to explore the site.</p>
  </div>
</template>

<script>
export default {
  computed: {
    user() {
      // Récupérer l'utilisateur depuis localStorage ou un store
      return JSON.parse(localStorage.getItem('user'));
    },
  },
  methods: {
    logout() {
      // Effacer la session utilisateur côté frontend
      localStorage.removeItem('user');
      this.$store.commit('clearUser'); // Si vous utilisez Vuex/Pinia
      this.$router.push('/login'); // Rediriger vers la page de connexion
    },
  },
};
</script>
