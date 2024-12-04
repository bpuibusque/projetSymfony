<template> 
    <nav>
      <ul>
        <router-link to="/"><img src="/logo-symfony.png" alt="Logo du site" id="site-logo"></router-link>
        <li><router-link to="/">Home</router-link></li>
        <li><router-link to="/commissions">Commissions</router-link></li>
        <li><router-link to="/private-messages">Private Messages</router-link></li>
        <li><router-link to="/notifications">Notifications</router-link></li>
        <li v-if="!user"><router-link to="/login">Login</router-link></li>
        <li v-if="user">
          <a href="#" @click.prevent="logout">Deconnexion</a>
        </li>
      </ul>
    </nav>
  </template>
  
  <script>
  import { mapState } from 'vuex';
  
  export default {
    computed: {
      ...mapState(['user']),
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
  
  <style>
 nav ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    overflow: hidden;
    background-color: #25d366; /* Couleur de fond de la barre de navigation */
}

#site-logo {
  height: 60px;
  margin-right: 20px;
  float: right;
}

nav ul li {
    float: left;
}

nav ul li a, .dropbtn {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 20px 20px;
    text-decoration: none;
    font-weight: bold;
}

nav ul li a:hover, .dropdown:hover .dropbtn {
    background-color: #128C7E; /* Couleur de fond au survol */
}
  </style>
  