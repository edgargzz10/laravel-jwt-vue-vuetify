<template>
  <v-app-bar app color="primary">
    <v-toolbar-title>My App</v-toolbar-title>
    <v-spacer></v-spacer>
    <v-btn text to="/">Home</v-btn>
    <v-btn v-if="isLoggedIn" text to="/dashboard">Dashboard</v-btn>
    <v-btn v-if="!isLoggedIn" text to="/login">Login</v-btn>
    <v-btn v-if="isLoggedIn" text @click="logout">Logout</v-btn>
  </v-app-bar>
</template>

<script>
export default {
  computed: {
    isLoggedIn() {
      return localStorage.getItem("token") !== null;
    },
  },
  methods: {
    logout() {
      localStorage.removeItem("token");
      this.$router.push("/");
    },
  },
};
</script>
