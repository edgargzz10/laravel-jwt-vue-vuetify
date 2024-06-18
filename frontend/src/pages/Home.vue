<template>
  <div>
    <Navbar />
    <!-- Componente de navegación -->

    <v-container fluid>
      <!-- Contenido principal de la página de inicio -->
      <v-row justify="center">
        <v-col cols="12" md="8">
          <v-card>
            <v-card-title>¡Bienvenido a nuestra aplicación!</v-card-title>
            <v-card-text>
              <p>
                The application is a User Management System designed to
                administer and handle operations related to registered users.
                Its main functionality includes:
              </p>
              <p>
                User Registration: Allows users to register by providing
                information such as name, last name, email address, and
                password. It performs validations to ensure that the entered
                data meets specified requirements. Login: Registered users can
                log in using their credentials (email and password). The
                application authenticates users and securely grants access to
                their accounts. Profile Management: Users can view and update
                their profile information, including name, last name, and other
                personal details. The application validates and saves changes
                made by users to their profiles. User Administration:
                Administrators have the ability to view a list of all registered
                users in the system. They can perform operations such as editing
                a user's information, changing a user's password, and deleting
                users from the system..
              </p>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import Navbar from "@/components/Navbar"; // Asegúrate de ajustar la ruta según la ubicación de tu componente Navbar

export default {
  name: "HomePage",
  components: {
    Navbar,
  },
  data() {
    return {
      // Puedes agregar datos específicos de la página de inicio si es necesario
    };
  },
  methods: {
    // Puedes agregar métodos específicos de la página de inicio si es necesario
  },
};
</script>
