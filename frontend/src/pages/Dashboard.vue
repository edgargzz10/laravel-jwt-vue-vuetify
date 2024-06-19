<template>
  <div>
    <Navbar />
    <v-container>
      <v-alert v-model="showAlert" :type="alertType" dismissible>
        {{ alertMessage }}
      </v-alert>

      <v-data-table :headers="headers" :items="users" class="elevation-1">
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Welcome</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="openCreateUserDialog"
              >Crear Usuario</v-btn
            >
          </v-toolbar>
        </template>

        <template v-slot:item="{ item }">
          <tr>
            <td>{{ item.id }}</td>
            <td>{{ item.name }}</td>
            <td>{{ item.last_name }}</td>
            <td>{{ item.email }}</td>
            <td>
              <v-row justify="center">
                <!-- Use cols, sm, md, lg, xl props to adjust the layout for different screen sizes -->
                <v-col cols="12" sm="4" md="3" lg="2" xl="2">
                  <v-btn icon @click="editUser(item)">
                    <v-icon>mdi-pencil</v-icon>
                  </v-btn>
                </v-col>
                <v-col cols="12" sm="4" md="3" lg="2" xl="2">
                  <v-btn icon @click="changePassword(item)">
                    <v-icon>mdi-lock-reset</v-icon>
                  </v-btn>
                </v-col>
                <v-col cols="12" sm="4" md="3" lg="2" xl="2">
                  <v-btn icon @click="deleteUser(item.id)">
                    <v-icon>mdi-delete</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </td>
          </tr>
        </template>
      </v-data-table>
    </v-container>

    <!-- Create User -->
    <v-dialog v-model="createUserDialog">
      <v-card>
        <v-card-title>Create User</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="formData.name"
            label="Name"
            :error-messages="formErrors.name"
          ></v-text-field>
          <v-text-field
            v-model="formData.last_name"
            label="Last Name"
            :error-messages="formErrors.last_name"
          ></v-text-field>
          <v-text-field
            v-model="formData.email"
            label="Email"
            :error-messages="formErrors.email"
          ></v-text-field>
          <v-text-field
            v-model="formData.password"
            label="Password"
            type="password"
            :error-messages="formErrors.password"
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn @click="closeCreateUserDialog">Cancel</v-btn>
          <v-btn color="primary" @click="createUser">Create</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit User -->
    <v-dialog v-model="editDialog">
      <v-card>
        <v-card-title>Edit User</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="editedUser.name"
            label="Name"
            :error-messages="editErrors.name"
          ></v-text-field>
          <v-text-field
            v-model="editedUser.last_name"
            label="Last Name"
            :error-messages="editErrors.last_name"
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn @click="editDialog = false">Cancel</v-btn>
          <v-btn color="primary" @click="saveEditedUser">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Change Password -->
    <v-dialog v-model="passwordDialog">
      <v-card>
        <v-card-title>Change Password</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="newPassword"
            label="New Password"
            type="password"
            :error-messages="passwordErrors"
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn @click="passwordDialog = false">Cancel</v-btn>
          <v-btn color="primary" @click="saveChangedPassword">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import axios from "axios";

export default {
  name: "DashboardPage",
  data() {
    return {
      users: [],
      headers: [
        { text: "ID", value: "id" },
        { text: "Name", value: "name" },
        { text: "Last Name", value: "last_name" },
        { text: "Email", value: "email" },
        { text: "Actions", value: "actions", sortable: false },
      ],
      editDialog: false,
      passwordDialog: false,
      createUserDialog: false,
      editedUser: {
        id: null,
        name: "",
        last_name: "",
      },
      newPassword: "",
      formData: {
        name: "",
        last_name: "",
        email: "",
        password: "",
      },
      formErrors: {},
      editErrors: {},
      passwordErrors: [],
      showAlert: false,
      alertType: "",
      alertMessage: "",
    };
  },
  async created() {
    await this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      try {
        const token = localStorage.getItem("token");
        const response = await axios.get("http://localhost:8000/api/users", {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        this.users = response.data.users;
      } catch (error) {
        this.showAlertMessage("error", "Failed to fetch users.");
        console.error(error);
      }
    },

    async deleteUser(id) {
      try {
        const token = localStorage.getItem("token");
        const loggedInUserId = localStorage.getItem("userId");
        const loggedInUserIdNum = parseInt(loggedInUserId, 10);

        if (id === loggedInUserIdNum) {
          this.showAlertMessage("error", "You can eleminate your user.");
          return;
        }

        await axios.delete(`http://localhost:8000/api/users/${id}`, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });

        await this.fetchUsers();
        this.showAlertMessage("success", "User deletes.");
      } catch (error) {
        this.showAlertMessage("error", "User deleted error.");
        console.error(error);
      }
    },
    editUser(user) {
      this.editedUser = { ...user };
      this.editErrors = {};
      this.editDialog = true;
    },

    async saveEditedUser() {
      try {
        const token = localStorage.getItem("token");
        await axios.put(
          `http://localhost:8000/api/users/${this.editedUser.id}`,
          this.editedUser,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.editDialog = false;
        await this.fetchUsers();
        this.showAlertMessage("success", "User updated successfully.");
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.editErrors = error.response.data.errors || {};
        } else {
          this.showAlertMessage("error", "Failed to update user.");
          console.error(error);
        }
      }
    },
    changePassword(user) {
      this.editedUser = user;
      this.passwordDialog = true;
    },
    async saveChangedPassword() {
      try {
        const token = localStorage.getItem("token");
        const { id } = this.editedUser;

        const data = {
          new_password: this.newPassword,
        };

        await axios.post(
          `http://localhost:8000/api/users/${id}/change-password`,
          data,
          {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          }
        );

        this.passwordDialog = false;
        this.passwordErrors = [];
        this.showAlertMessage("success", "Password changed succesfully.");
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.passwordErrors = error.response.data.errors.new_password || [];
        } else {
          this.showAlertMessage("error", "Change password error");
          console.error(error);
        }
      }
    },
    async createUser() {
      try {
        const token = localStorage.getItem("token");
        await axios.post("http://localhost:8000/api/users", this.formData, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        });
        this.createUserDialog = false;
        await this.fetchUsers();
        this.showAlertMessage("success", "User created.");
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.formErrors = error.response.data.errors || {};
        } else {
          this.showAlertMessage("error", "User error.");
          console.error(error);
        }
      }
    },
    openCreateUserDialog() {
      this.createUserDialog = true;
      this.resetFormData();
    },
    closeCreateUserDialog() {
      this.createUserDialog = false;
      this.resetFormData();
    },
    resetFormData() {
      this.formData = {
        name: "",
        last_name: "",
        email: "",
        password: "",
      };
      this.formErrors = {};
    },
    showAlertMessage(type, message) {
      this.alertType = type;
      this.alertMessage = message;
      this.showAlert = true;

      setTimeout(() => {
        this.showAlert = false;
      }, 3000);
    },
    logout() {
      localStorage.removeItem("token");
      localStorage.removeItem("userId");
      this.$router.push("");
    },
  },
};
</script>
