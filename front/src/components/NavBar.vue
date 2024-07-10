<template>
    <nav>
      <ul>
        <li><router-link to="/">Home</router-link></li>
        <li><router-link to="/commissions">Commissions</router-link></li>
        <li><router-link to="/private-messages">Private Messages</router-link></li>
        <li v-if="!user"><router-link to="/login">Login</router-link></li>
        <li v-if="!user"><router-link to="/register">Register</router-link></li>
        <li v-if="user">
          <span @click="logout">{{ user.email }}</span>
        </li>
      </ul>
    </nav>
  </template>
  
  <script>
  import { mapState, mapActions } from 'vuex';
  
  export default {
    computed: {
      ...mapState(['user']),
    },
    methods: {
      ...mapActions(['logout']),
    },
  };
  </script>
  
  <style>
  nav ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
    overflow: hidden;
    background-color: #333;
  }
  nav ul li {
    float: left;
  }
  nav ul li a, .dropbtn {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
  }
  nav ul li a:hover, .dropdown:hover .dropbtn {
    background-color: #111;
  }
  nav ul li.dropdown {
    display: inline-block;
  }
  .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
  }
  .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    text-align: left;
  }
  .dropdown-content a:hover {background-color: #f1f1f1;}
  .dropdown:hover .dropdown-content {
    display: block;
  }
  </style>
  