<template>
  <div class="login-container">
    <div class="login-content">
      <div class="login-header">
        <h1 class="signin-title"><span class="title">Connexion</span></h1>
      </div>
      <div class="login-body">
        <form @submit.prevent="submitForm" class="login-form">
          <div v-if="error" class="alert alert-danger">{{ errorMessage }}</div>

          <label for="username">Email</label>
          <input
            type="email"
            v-model="username"
            id="username"
            class="form-control"
            autocomplete="email"
            required
            autofocus
          />
          <label for="password">Mot de passe</label>
          <input
            type="password"
            v-model="password"
            id="password"
            class="form-control"
            autocomplete="current-password"
            required
          />

          <div class="login-footer">
            <button class="btn btn-lg btn-primary btn-block" type="submit">
              Connexion
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      error: false,
      errorMessage: '',
      user: null,
      userIdentifier: '',
    };
  },
  methods: {
    async submitForm() {
      try {
        const response = await fetch('http://localhost:8000/api/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            email: this.username,
            password: this.password
          })
        });
        const data = await response.json();
        if (data.token) {
          // Stocker les informations utilisateur et token
          localStorage.setItem('authToken', data.token);
          localStorage.setItem('user', JSON.stringify(data.user));
          this.$store.commit('setUser', data.user); // Optionnel si vous utilisez Vuex/Pinia
          this.$router.push('/'); // Redirigez vers une autre page
        } else {
          this.error = true;
          this.errorMessage = 'Invalid credentials';
        }
      } catch (err) {
        this.error = true;
        this.errorMessage = 'An error occurred. Please try again.';
      }
    },
  },
};
</script>

<style scoped>
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f8f8f8;
  font-family: Arial, sans-serif;
}

.login-content {
  width: 100%;
  max-width: 400px; /* Ajuste la largeur selon tes besoins */
  text-align: center;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 2rem;
}

.signin-title {
  font-size: 24px; /* Taille de police plus grande */
  font-weight: bold; /* Texte en gras */
  margin-top: 0; /* Supprime la marge supérieure pour rapprocher du bord */
  color: #25d366; /* Couleur spécifique */
}

.login-body {
  margin-bottom: 1rem;
}

.login-form {
  text-align: left;
}
.title{
  font-weight: 1000;
  font-size: 45px;
}

.login-container label {
  display: block;
  margin: 0.5rem 0 0.2rem;
  text-align: left;
}

.login-container input {
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.login-container .btn-primary {
  background-color: #25d366;
  border-color: #25d366;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
}

.login-container .btn-primary:hover {
  background-color: #1da851;
}
</style>
