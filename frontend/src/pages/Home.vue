<template>
  <div>
    <Navbar />

    <v-container fluid class="pt-5">
      <v-row justify="center">
        <v-col cols="12" md="8">
          <v-card class="elevation-3">
            <v-card-title class="display-1 text-center primary--text">
              USER MANAGER SYSTEM
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
              <v-row>
                <v-col cols="12">
                  <p class="subtitle-1">
                    The application is a User Management System designed to
                    administer and handle operations related to registered
                    users. Its main functionality includes:
                  </p>
                </v-col>
                <v-col cols="12">
                  <p class="subtitle-1">
                    User Registration: Allows users to register by providing
                    information such as name, last name, email address, and
                    password. User Administration:
                  </p>
                </v-col>
                <v-col cols="12">
                  <p class="subtitle-1">
                    It performs validations to ensure that the entered data
                    meets specified requirements. Login: Registered users can
                    log in using their credentials (email and password). The
                    application authenticates users and securely grants access
                    to their accounts.
                  </p>
                </v-col>
                <v-col cols="12">
                  <p class="subtitle-1">
                    Profile Management: Users can view and update their profile
                    information, including name, last name, and other personal
                    details.
                  </p>
                </v-col>
                <v-col cols="12">
                  <p class="subtitle-1">
                    The application validates and saves changes made by users to
                    their profiles.
                  </p>
                </v-col>
                <v-col cols="12">
                  <p class="subtitle-1">
                    Administrators have the ability to view a list of all
                    registered users in the system. They can perform operations
                    such as editing a user's information, changing a user's
                    password, and deleting users from the system.
                  </p>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import Navbar from "@/components/Navbar";

export default {
  name: "HomePage",
  components: {
    Navbar,
  },
  data() {
    return {};
  },
  methods: {},
};
</script>
