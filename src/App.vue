<template>
  <v-app >
    <div v-if="session">
      <v-navigation-drawer class="hidden-xl-and-up" absolute temporary app left v-model="sideNav">
        <v-card>
          
          <v-img
              :src="fondoNav"
              height="300px"
          >
          </v-img>
          <v-divider></v-divider>
        </v-card>

        <v-list dense >
        
        <v-list-tile 
          v-for="(item) in menuItems" 
          :key="item.titulo"
          :to="item.ruta">

          <v-list-tile-action>
            <v-icon left>{{item.icono}}</v-icon>
          </v-list-tile-action>

          <v-list-tile-content>
            <v-list-tile-title>{{item.titulo}}</v-list-tile-title>
          </v-list-tile-content>

        </v-list-tile>
         <v-divider></v-divider>
         <v-list-tile 
          key="Salir"
          style="cursor:pointer"
          @click="salir">

          <v-list-tile-action>
            <v-icon left>fa-sign-out-alt</v-icon>
          </v-list-tile-action>

          <v-list-tile-content>
            <v-list-tile-title>Salir</v-list-tile-title>
          </v-list-tile-content>

        </v-list-tile>

        </v-list>


      </v-navigation-drawer>

      <v-toolbar dark class="primary">

        <v-toolbar-side-icon class="hidden-xl-and-up " @click.stop="sideNav=!sideNav"></v-toolbar-side-icon>
        
        <v-toolbar-title  >
          <router-link class="title  " to="/" tag="span" style="cursor:pointer">Redistribución de stocks</router-link>
        </v-toolbar-title>
        
        
        <v-spacer></v-spacer>

        <v-toolbar-items  class="hidden-lg-and-down" >

          <v-btn flat 
            v-for="item in menuItems" 
            :key="item.title"
            :to="item.ruta" 
            style="cursor:pointer"
          >
            <v-icon left>{{item.icono}}</v-icon>
            {{item.titulo}}
          </v-btn>
          <v-btn flat 
            key="Salir"
            style="cursor:pointer"
            @click="salir"
          >
            <v-icon left>fa-sign-out-alt</v-icon>
            Salir
          </v-btn>
        </v-toolbar-items>

      </v-toolbar>
    </div>

    <main class="blue-grey lighten-5">
      
      <router-view/> 

    </main>

    <v-footer v-if="session" color="primary" app inset>
      <span class="white--text">Farmacia drogueria San Jorge &copy; 2018</span>
    </v-footer>
  </v-app>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component
export default class App extends Vue {
  protected path:string='http://localhost/sobrecostos-2/api/';
  public sideNav: boolean = false;
  public menuItems = [
    { icono: 'fa-file-invoice', titulo: 'Cargar pla_remi copidrogas', ruta: '/upcopi'},
    { icono: 'fa-clipboard-list', titulo: 'Redistribucion de stocks', ruta: '/remisiones'},
    { icono: 'fa-truck', titulo: 'Verificar transferencias', ruta: '/transferencias'},
  ];
  public fondoNav = require('@/assets/fondos/logo2.svg');
  private session:boolean= false;

  private mounted() {

    if (localStorage.session) {
      this.session=true;
    }
    
    if(localStorage.perfil==1){
      this.menuItems.unshift(
        {icono: 'fa-users', titulo: 'Usuarios', ruta: '/usuarios'},
        {icono: 'fa-file-upload', titulo: 'Subir consolidado', ruta: '/upfile'},
      );
    }else if(localStorage.perfil=2){
      this.menuItems.unshift(
        {icono: 'fa-file-upload', titulo: 'Subir consolidado', ruta: '/upfile'},
      );
    }

  }

  private salir(){
      const path:string=this.path+'api/';
      this.axios
      .post(path+'salir', {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      .then((res) => {
        
        if (res.data) {
          localStorage.removeItem('session');
          localStorage.removeItem('id');
          localStorage.removeItem('nombre');
          localStorage.removeItem('usuario');
          localStorage.removeItem('perfil');
          localStorage.removeItem('sede ');

          location.reload();
        } else {
          alert('Error');
        }
      })
      .catch((error) => {
        // console.error(error);
      });

    localStorage.removeItem('session');
    localStorage.removeItem('nombre');
    localStorage.removeItem('usuario');
    location.reload();

  }

  
}

</script>

<style>
body {
  display: flex;
  min-height: 100vh;
  flex-direction: column;
}

main {
  flex: 1 0 auto;
}
h1 {
  font-size: 300%;
}

 .file-upload .file-upload-main{
  
  display: flex;
  align-items: center;
  position: relative;
  min-height: 100px;
  border: 2px dashed green;
  
}
.file-upload .file-upload-main:hover p{
    font-size:20px;
}
.file-upload .file-upload-main:hover p{
    font-size:25px;
}

.file-upload input[type=file]{
    
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
  width: 100%;
  height: 100%;
  cursor: pointer;
}
.file-upload .file-upload-res{
  width: 100%;
  text-align: center;
  color:gray;
}

.form{
  max-height:55vh;
  overflow-y:scroll;
}



</style>
