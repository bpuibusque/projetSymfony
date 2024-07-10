<template>
  <div>
    <h1>Welcome to our Application!</h1>
    <p>This is the homepage. Use the navigation menu to explore the site.</p>
  </div>
</template>

<script>
export default {
  name: 'HomePage',
  created() {
    this.$store.dispatch('fetchUser');
  },
};
</script>
