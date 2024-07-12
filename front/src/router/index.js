import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../views/HomePage.vue';
import CommissionPage from '../views/CommissionPage.vue';
import PrivateMessages from '../views/PrivateMessages.vue';
import LoginPage from '../views/LoginPage.vue';
import RegisterPage from '../views/RegisterPage.vue';

const routes = [
  { path: '/', name: 'HomePage', component: HomePage },
  { path: '/commissions', name: 'CommissionPage', component: CommissionPage },
  { path: '/private-messages', name: 'PrivateMessages', component: PrivateMessages },
  { path: '/login', name: 'LoginPage', component: LoginPage },
  { path: '/register', name: 'RegisterPage', component: RegisterPage },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
