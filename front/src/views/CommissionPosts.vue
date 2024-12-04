<template>
  <div class="posts-container">
    <div class="header">
      <h2 class="title">{{ commissionName }}</h2>
      <button @click="createNewPost" class="new-post-btn">Nouveau Post</button>
    </div>
    <div v-if="posts.length" class="posts-grid">
      <div v-for="post in posts" :key="post.id" class="post-card">
        <h3 class="post-title">{{ post.title }}</h3>
        <p class="post-content">{{ post.content }}</p>
        <div class="post-info">
          <span class="author">Posté par : {{ post.user.email }}</span>
          <span class="date">le {{ new Date(post.createdAt).toLocaleString() }}</span>
        </div>
        <button
          v-if="isUserPost(post.user.email)"
          @click="deletePost(post.id)"
          class="delete-post-btn"
        >
          Supprimer
        </button>
      </div>
    </div>
    <div v-else class="no-posts">
      <p>Aucun post disponible pour cette commission.</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'CommissionPosts',
  data() {
    return {
      posts: [],
      commissionName: '',
      currentUserEmail: 'user@example.com', // Remplacez par une méthode pour obtenir l'email de l'utilisateur connecté
    };
  },
  created() {
    this.fetchPosts();
  },
  methods: {
    fetchPosts() {
      const commissionId = this.$route.params.id;
      const url = `/commissions/api/${commissionId}/posts`;

      axios
        .get(url)
        .then((response) => {
          this.posts = response.data.posts;
          this.commissionName = response.data.commissionName;
        })
        .catch((error) => {
          console.error('Erreur lors de la récupération des posts:', error);
        });
    },
    isUserPost(email) {
      return this.currentUserEmail === email;
    },
    deletePost(postId) {
      const url = `/posts/${postId}/delete`;
      axios
        .delete(url)
        .then(() => {
          this.posts = this.posts.filter((post) => post.id !== postId);
        })
        .catch((error) => {
          console.error('Erreur lors de la suppression du post:', error);
        });
    },
    createNewPost() {
      const commissionId = this.$route.params.id;
      this.$router.push(`/commissions/${commissionId}/new-post`);
    },
  },
  watch: {
    '$route.params.id': 'fetchPosts',
  },
};
</script>

<style>
/* Global container for posts */
.posts-container {
  max-width: 900px;
  margin: 0 auto;
  padding: 20px;
  font-family: 'Arial', sans-serif;
  background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Header styling */
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

/* Title styling */
.title {
  font-size: 28px;
  color: #333;
  text-transform: uppercase;
  letter-spacing: 2px;
  margin: 0;
}

/* New Post Button */
.new-post-btn {
  background: #007bff;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  transition: background 0.3s ease;
}

.new-post-btn:hover {
  background: #0056b3;
}

/* Grid layout for posts */
.posts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 20px;
}

/* Individual post card styling */
.post-card {
  background: #ffffff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
}

.post-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Delete Post Button */
.delete-post-btn {
  background: #dc3545;
  color: white;
  border: none;
  padding: 8px 12px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  margin-top: 10px;
  transition: background 0.3s ease;
}

.delete-post-btn:hover {
  background: #c82333;
}

/* Post info styling */
.post-info {
  display: flex;
  justify-content: space-between;
  font-size: 14px;
  color: #777;
}

/* No posts message */
.no-posts {
  text-align: center;
  font-size: 18px;
  color: #888;
  background: #f8f8f8;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #ddd;
}
</style>
