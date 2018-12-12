<template>
  <v-container grid-list-md>
    
    <v-card row wrap >

      <v-card-title>
        Usuarios
        <v-spacer></v-spacer>

        <v-dialog v-model="dialog" max-width="500px">
          <v-btn slot="activator" color="primary" dark class="mb-2"><v-icon>fas fa-user-plus</v-icon></v-btn>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container grid-list-md>
                <v-layout wrap>
                  <v-flex xs12>
                    <v-text-field v-model="editedItem.name" label="Nombre"></v-text-field>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field v-model="editedItem.calories" label="Cedual"></v-text-field>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field v-model="editedItem.fat" label="Usuario"></v-text-field>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field v-model="editedItem.carbs" label="Perfil"></v-text-field>
                  </v-flex>
                  <v-flex xs12>
                    <v-text-field v-model="editedItem.protein" type="password" label="Contraseña"></v-text-field>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="accent darken-1" flat @click="close">Cancelar</v-btn>
              <v-btn color="accent darken-1" flat @click="save">Guardar</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-card-title>
      <v-card-title>
        
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
        item-key="name"
        class="elevation-1"
        
      >
        
        <template slot="items" slot-scope="props">
          <td class="text-xs-left">{{props.item.nombre}}</td>
          <td class="text-xs-left">{{props.item.usuario}}</td>
          <td class="text-xs-left">{{props.item.cedula}}</td>
          <td class="text-xs-left">{{ props.item.perfil }}</td>
          <td class="text-xs-left">
            <v-icon
              small
              class="mr-2 "
              @click="editItem(props.item)"
            >
              fas fa-user-edit
            </v-icon>
          </td>

        
        </template>
        <template slot="no-data">
          <v-btn  fab color="primary" @click="loadUsers">
            <v-icon>fas fa-sync-alt</v-icon>
          </v-btn>
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
  
  private editedIndex: number= -1;

  private dialog:boolean = false;

  private snack={
    color: '',
    text:'',
    show:false,
  };
  private  editedItem= {
    name: '',
    calories: 0,
    fat: 0,
    carbs: 0,
    protein: 0
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
  created() {
    this.loadUsers();
  }
  get formTitle () {
    return this.editedIndex === -1 ? 'Nuevo Usuario' : 'Editar Usuario'
  }
  private loadUsers(){
    const path = "http://localhost/sobrecostos/api/usuarios";
    this.axios
      .get(path, {
        params: {
          perfil: localStorage.perfil
        }
      })
      .then(res => {
        
        if(res.data) {
          if(res.data.estado) {
            this.items=res.data.contenido;
            console.log(this.items);
          }
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
      });
  }

  private editItem (item:any) {
    // this.editedIndex = this.desserts.indexOf(item)
    // this.editedItem = Object.assign({}, item)
    // this.dialog = true
  }

  private deleteItem (item:any) {
    // const index = this.desserts.indexOf(item)
    // confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
  }

  private close () {
    this.dialog = false
    // setTimeout(() => {
    //   this.editedItem = Object.assign({}, this.defaultItem)
    //   this.editedIndex = -1
    // }, 300)
  }

  private save () {
    // if (this.editedIndex > -1) {
    //   Object.assign(this.desserts[this.editedIndex], this.editedItem)
    // } else {
    //   this.desserts.push(this.editedItem)
    // }
    this.close()
  }
}
</script>