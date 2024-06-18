<template>
  <div class="login-page">
    <Navbar />

    <v-container class="fill-height d-flex align-center justify-center">
      <v-row justify="center">
        <v-col cols="12" sm="8" md="6">
          <v-form @submit.prevent="login" class="pa-8 text-center">
            <v-text-field
              label="Email"
              v-model="email"
              :error-messages="emailErrors"
            ></v-text-field>

            <v-text-field
              label="Password"
              v-model="password"
              type="password"
              :error-messages="passwordErrors"
            ></v-text-field>

            <v-alert v-if="loginError" type="error" dismissible>
              {{ loginError }}
            </v-alert>

            <v-btn color="primary" type="submit">Login</v-btn>
          </v-form>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "LoginPage",
  data() {
    return {
      email: "",
      password: "",
      emailErrors: [],
      passwordErrors: [],
      loginError: "",
    };
  },
  methods: {
    async login() {
      try {
        this.emailErrors = [];
        this.passwordErrors = [];
        this.loginError = "";

        const response = await axios.post("http://localhost:8000/api/login", {
          email: this.email,
          password: this.password,
        });

        localStorage.setItem("token", response.data.token);
        localStorage.setItem("userId", response.data.user.id);

        this.$router.push("/dashboard");
      } catch (error) {
        if (error.response.status === 422) {
          const errors = error.response.data.errors;
          if (errors.email) {
            this.emailErrors = errors.email;
          }
          if (errors.password) {
            this.passwordErrors = errors.password;
          }
        } else if (error.response.status === 401) {
          this.loginError = "Email y/o contraseña incorrectos.";
        } else {
          console.error(error);
        }
      }
    },
  },
};
</script>

<style scoped></style>
