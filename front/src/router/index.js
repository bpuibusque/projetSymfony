import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../views/HomePage.vue';
import CommissionPage from '../views/CommissionPage.vue';
import PrivateMessages from '../views/PrivateMessages.vue';

const routes = [
  { path: '/', name: 'HomePage', component: HomePage },
  { path: '/commissions', name: 'CommissionPage', component: CommissionPage },
  { path: '/private-messages', name: 'PrivateMessages', component: PrivateMessages },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
