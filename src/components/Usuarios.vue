<template>
  <v-container grid-list-md>
    
    <v-card row wrap >

      <v-card-title>
        Usuarios
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          append-icon="search"
          label="search"
          single-line
          hide-details
          
        ></v-text-field>
      </v-card-title>
    
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
        select-all
        item-key="name"
        class="elevation-1"
        
      >

        <template slot="items" slot-scope="props">

        
          <td >

            <v-checkbox v-model="props.item.selected" primary hide-details ></v-checkbox>

          </td>
          <td class="text-xs-left">{{props.item.nombre}}</td>
          <td class="text-xs-left">{{props.item.usuario}}</td>
          <td class="text-xs-left">{{props.item.cedula}}</td>
          <td class="text-xs-left">{{ props.item.perfil }}</td>

        
        </template>
         <v-alert slot="no-results" :value="true" color="error" icon="warning">
          No se contraron resultador para "{{ search }}".
        </v-alert>
      </v-data-table>
      <v-snackbar v-model="snack.show" :timeout="3000" :color="snack.color">
        {{ snack.text }}
        <v-btn flat @click="snack.show = false">Close</v-btn>
      </v-snackbar>

      <div class="text-xs-right pt-2">

        <!--
        <v-btn color="secondary" @click="transladar">
        -->
        <v-btn color="secondary">

          <v-icon>fa-save</v-icon>
          <span>Guardar</span>

        </v-btn>

      </div>
      
    </v-card> 
    
    <!-- INPUTS FORMULARIO -->
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';


@Component
export default class Tabla extends Vue {
  
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  private path:string='http://192.168.0.10/sobrecostos/api/';
  private search:string=' ';
  private items: object[] = [];
  
  private snack={
    color: '',
    text:'',
    show:false,
  };
  
  private headers = [
    { text: 'Nombre', value: 'nom' },
    { text: 'Usuario ', value: 'user' },
    { text: 'Cedula', value: 'cc' },
    { text: 'Perfil', value: 'perfil' },
    { text: 'modificar', value: 'mod' },
  ];
  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  constructor() {
    super();
    const path = "http://localhost/sobrecostos/api/usuarios";
    this.axios
      .get("http://localhost/sobrecostos/api/usuarios", {
        params: {
          dato: 'all'
        }
      })
      .then(res => {
        console.log(res.data)  
        if(res.data) {
          if(res.data.estado) {
            console.log(res.data)  
            this.items=res.data;
          }
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
      });
  }
  
}
</script>