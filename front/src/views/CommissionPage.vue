<template>
  <div>
    <h1>Commissions</h1>
    <div v-if="commissions.length">
      <ul class="nav nav-tabs">
        <li class="nav-item" v-for="commission in commissions" :key="commission.id">
          <router-link :to="{ name: 'CommissionPosts', params: { id: commission.id } }" class="nav-link">
            {{ commission.name }}
          </router-link>
        </li>
      </ul>
      <router-view />
    </div>
    <div v-else>
      <p>No commissions available.</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CommissionPage',
  data() {
    return {
      commissions: []
    };
  },
  created() {
    this.fetchCommissions();
  },
  methods: {
    fetchCommissions() {
      axios.get('/api/commissions')
        .then(response => {
          this.commissions = response.data;
        })
        .catch(error => {
          console.error('Error fetching commissions:', error);
        });
    }
  }
};
</script>

<style>
/* Add any styles you need here */
</style>
