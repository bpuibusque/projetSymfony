<template>
  <div>
    <h2>{{ commissionName }}</h2>
    <div v-if="posts.length">
      <div v-for="post in posts" :key="post.id" class="post">
        <h3>{{ post.title }}</h3>
        <p>{{ post.content }}</p>
        <small>Posted by {{ post.user.email }} on {{ new Date(post.createdAt).toLocaleString() }}</small>
      </div>
    </div>
    <div v-else>
      <p>No posts available for this commission.</p>
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
      commissionName: ''
    };
  },
  created() {
    this.fetchPosts();
  },
  methods: {
    fetchPosts() {
      const commissionId = this.$route.params.id;
      const url = commissionId && commissionId !== 'general'
        ? `/commissions/api/${commissionId}/posts`
        : '/commissions/api/general/posts';
        
      axios.get(url)
        .then(response => {
          this.posts = response.data.posts;
          this.commissionName = response.data.commissionName || 'General';
        })
        .catch(error => {
          console.error('Error fetching posts:', error);
        });
    }
  },
  watch: {
    '$route.params.id': 'fetchPosts'
  }
};
</script>

<style>
.post {
  border-bottom: 1px solid #ddd;
  padding: 15px 0;
}
</style>
