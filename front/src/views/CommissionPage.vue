<template>
  <div>
    <h1>Commissions</h1>
    <div v-if="commissions.length">
      <div class="nav-container">
        <button 
          v-for="commission in commissions" 
          :key="commission.id" 
          @click="setActiveCommission(commission.id)"
          :class="{ active: isActive(commission.id) }"
          class="nav-button"
        >
          {{ commission.name }}
        </button>
      </div>
      <div class="post-container">
        <router-view />
      </div>
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
      commissions: [],
      activeCommission: 'general'
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
          console.error('Error fetching commissions:', error);
        });
    },
    setActiveCommission(id) {
      this.activeCommission = id;
      this.$router.push(`/commissions/${id}/posts`);
    },
    isActive(commissionId) {
      return this.activeCommission === String(commissionId);
    }
  },
};
</script>

<style>
.nav-container {
  display: flex;
  margin-bottom: 20px;
  border-bottom: 1px solid #ddd;
}
.nav-button {
  cursor: pointer;
  padding: 10px 15px;
  border: 1px solid transparent;
  border-radius: 4px 4px 0 0;
  background-color: #f8f9fa;
  color: #007bff;
  margin-right: 10px;
}
.nav-button:hover {
  background-color: #e9ecef;
}
.nav-button.active {
  background-color: #ffffff;
  border-color: #dee2e6 #dee2e6 #fff;
  color: #495057;
}
.post-container {
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #ffffff;
}
.post {
  border-bottom: 1px solid #ddd;
  padding: 15px 0;
}
</style>
