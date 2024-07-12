<template>
  <div class="login-container">
    <div class="login-content">
      <div class="login-header">
        <h1 class="register-title"><span class="title">Register</span></h1>
      </div>
      <div class="login-body">
        <form @submit.prevent="submitForm" class="login-form">
          <div v-if="errors.length" class="alert alert-danger">
            <ul>
              <li v-for="error in errors" :key="error">{{ error }}</li>
            </ul>
          </div>

          <label for="prenom">Prenom</label>
          <input
            type="text"
            v-model="prenom"
            id="prenom"
            class="form-control"
            autocomplete="prenom"
            required
          />

          <label for="nom">Nom</label>
          <input
            type="text"
            v-model="nom"
            id="nom"
            class="form-control"
            autocomplete="nom"
            required
          />

          <label for="email">Email</label>
          <input
            type="email"
            v-model="email"
            id="email"
            class="form-control"
            autocomplete="email"
            required
          />

          <label for="plainPassword">Password</label>
          <input
            type="password"
            v-model="plainPassword"
            id="plainPassword"
            class="form-control"
            autocomplete="new-password"
            required
          />

          <div class="form-check form-check-inline" style="display: flex; align-items: center;">
           
            <label for="agreeTerms" class="form-check-label-inline">
            <input
              type="checkbox"
              v-model="agreeTerms"
              id="agreeTerms"
              class="form-check-input"
              required
              
              
            /><span class="terms">
              Agree to terms </span>
            </label>
             
          </div>

          <div class="login-footer">
            <button class="btn btn-lg btn-primary btn-block" type="submit">
              Register
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
      prenom: '',
      nom: '',
      email: '',
      plainPassword: '',
      agreeTerms: false,
      errors: []
    };
  },
  methods: {
    async submitForm() {
      this.errors = [];
      if (!this.prenom || !this.nom || !this.email || !this.plainPassword || !this.agreeTerms) {
        this.errors.push('All fields are required.');
        return;
      }

      try {
        const response = await fetch('http://localhost:3000/api/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            prenom: this.prenom,
            nom: this.nom,
            email: this.email,
            plainPassword: this.plainPassword,
            agreeTerms: this.agreeTerms
          })
        });

        const data = await response.json();
        if (!response.ok) {
          this.errors.push(data.message || 'An error occurred. Please try again.');
        } else {
          // Handle successful registration (e.g., redirect or show success message)
        }
      } catch (err) {
        this.errors.push('An error occurred. Please try again.');
      }
    }
  }
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

.register-title {
  font-size: 24px; /* Taille de police plus grande */
  font-weight: bold; /* Texte en gras */
  margin-top: 0; /* Supprime la marge supérieure pour rapprocher du bord */
  color: #25d366; /* Couleur spécifique */
}

.login-body {
  margin-bottom: 1rem;
}

.title{
  font-weight: 1000;
  font-size: 45px;
}

.login-form {
  text-align: left;
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

.form-check-label-inline{

  display: inline-block !important;
  white-space: nowrap !important;
  padding-bottom: 19px;
}

.form-check-input{
  vertical-align: middle !important;
  margin : 0 !important;
  width : 20% !important;
}

.temrs{
vertical-align: middle;
margin: 0;
width: 10%;
}

</style>
