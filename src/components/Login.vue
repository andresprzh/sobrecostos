<template>
    <div id="login">
        <v-app id="inspire">
            <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>  
                <v-flex xs12 sm8 md4  >
                    <div id=logo>
                        <img 
                        class="responsive-img"
                        id="logo"
                        :src="imagenrc"
                        >
                    </div>
                    <v-card class="elevation-12" style="height:250px;">
                    <v-form v-on:submit.prevent="login()">  
                    <v-card-text >
                        
                        <v-text-field prepend-icon="fa-user" name="login" label="Ingrese Usuario" type="text" v-model="input.username"></v-text-field>
                        <v-text-field prepend-icon="fa-lock" name="password" label="Contraseña" id="password" type="password" v-model="input.password"></v-text-field>
                        
                    </v-card-text>
                    <v-card-actions>

                        <v-btn  id="submit" type="submit" color="primary">ingresar</v-btn>      
                    </v-card-actions>
                    </v-form>
                    </v-card>
                </v-flex>
                </v-layout>
            </v-container>
            </v-content>
        </v-app>
    </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import App from '@/App.vue'; // @ is an alias to /src

@Component
export default class Login extends App {
  // variable que almacena los datos de inicio de sesion
  private input = {
    username: '',
    password: '',
  };

  private imagenrc: any = require("@/assets/fondos/logo2.svg");

  // metodo que valida el usurio
  private login() {
    if (this.input.username !== '' && this.input.password !== '') {
      const path:string = this.path+'login';
      const formData = new FormData();
      formData.append('username', this.input.username);
      formData.append('password', this.input.password);
      this.axios
        .post(path, formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        })
        .then((res) => {
          console.log(res.data);
          if (res.data) {
            // this.$emit('authenticated', true);
            localStorage.session=true;
            localStorage.id=res.data.id;
            localStorage.nombre=res.data.nombre;
            localStorage.usuario=res.data.usuario;
            localStorage.perfil=res.data.perfil;
            localStorage.sede=res.data.sede;

            location.reload();
            this.$router.replace({ name: 'home' });
          } else {
            alert('Usuario o contraseña incorrectos');
          }
        })
        .catch((error) => {
          // console.error(error);
        });
    }
  }
}
</script>

<style scoped>
#inspire {
  background: url("../assets/plantilla/back.png");

  background-size: cover;
}
#submit {
  width: 100%;
}#logo {
  display: flex;
  justify-content: center;
  padding-bottom: 5px;
}
#logo img {
  height: 30vh;
}
</style>