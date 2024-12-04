import { createRouter, createWebHistory } from 'vue-router';
import HomePage from '../views/HomePage.vue';
import CommissionPage from '../views/CommissionPage.vue';
import CommissionPosts from '../views/CommissionPosts.vue';
import PrivateMessages from '../views/PrivateMessages.vue';
import LoginPage from '../views/LoginPage.vue';
import RegisterPage from '../views/RegisterPage.vue';
import NewPost from '../views/NewPost.vue';
import NotificationIndex from '../views/NotificationIndex.vue';
import CreateNotification from '../views/CreateNotification.vue';
import NewPrivateMessage from '../views/NewPrivateMessage.vue';

const routes = [
  { path: '/', name: 'HomePage', component: HomePage },
  { path: '/commissions', name: 'CommissionPage', component: CommissionPage },
  { path: '/commissions/:id/posts', name: 'CommissionPosts', component: CommissionPosts },
  { path: '/commissions/:id/new-post', name: 'NewPost', component: NewPost }, 
  { path: '/private-messages', name: 'PrivateMessages', component: PrivateMessages },
  { path: '/login', name: 'LoginPage', component: LoginPage },
  { path: '/register', name: 'RegisterPage', component: RegisterPage },
  { path: '/notifications', name: 'NotificationIndex', component: NotificationIndex },
  { path: '/notifications/create', name: 'CreateNotification', component: CreateNotification,},
  { path: '/private_message/new', name: 'NewPrivateMessage', component: NewPrivateMessage }, 
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
