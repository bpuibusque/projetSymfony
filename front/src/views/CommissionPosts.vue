<template>
  <div>
    <h2>{{ commissionName }}</h2>
    <ul>
      <li v-for="post in posts" :key="post.id">{{ post.title }}</li>
    </ul>
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
      axios.get(`/api/commissions/${commissionId}/posts`)
        .then(response => {
          this.posts = response.data.posts;
          this.commissionName = response.data.name;
        })
        .catch(error => {
          console.error('Error fetching posts:', error);
        });
    }
  }
};
</script>
