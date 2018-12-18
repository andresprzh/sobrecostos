<template>
  <v-container grid-list-md>
    
    <v-card row wrap >

      <v-card-title>
        Usuarios
        <v-spacer></v-spacer>

        <v-dialog v-model="dialog" max-width="500px">

          <v-btn slot="activator" color="primary" dark class="mb-2"><v-icon>fas fa-user-plus</v-icon></v-btn>
          <v-card>

            <v-form ref="form_modal"  :v-model="valid_dialog" @submit="save">

              <v-card-title>
                <span class="headline">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container grid-list-md>
                  <v-layout wrap>
                    <v-flex xs12 v-for="(card,index) in editUser" :key="card.id"> 
                      
                      <v-select v-if="card.tipo==='select'"
                        ref="menu"
                        v-validate="card.validacion"
                        :items="card.items"
                        item-text="valor"
                        item-value="id"
                        v-model="card.dato"
                        :error-messages="errors.collect(card.id)"
                        :label="card.titulo"
                        :data-vv-name="card.id"
                        required
                      ></v-select>

                      <v-text-field v-else
                        ref="menu"
                        v-model="card.dato"
                        v-validate="card.validacion"
                        :type="card.tipo"
                        :error-messages="errors.collect(card.id)"
                        :label="card.titulo"
                        :data-vv-name="card.id "
                        required>
                      </v-text-field>

                    </v-flex>

                  </v-layout>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="accent darken-1" flat @click="dialog=false">Cancelar</v-btn>
                <v-btn color="accent darken-1" flat type="submit">Guardar</v-btn>
              </v-card-actions>

            </v-form>
            
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
        :items="filtered"
        class="elevation-1">
        
        <template slot="items" slot-scope="props">
          <td class="text-xs-left">{{props.item.nombre}}</td>
          <td class="text-xs-left">{{props.item.usuario}}</td>
          <td class="text-xs-left">{{props.item.cedula}}</td>
          <td class="text-xs-left">{{props.item.des_perfil}}</td>
          <td class="text-xs-left">{{props.item.des_sede}}</td>
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
      </v-data-table>

      <v-snackbar v-model="snack.show" :timeout="3000" :color="snack.color">
        {{ snack.text }}
        <v-btn flat @click="snack.show = false">Close</v-btn>
      </v-snackbar>
      
    </v-card> 
    
  </v-container>

</template>

<script lang="ts">
import { Component, Vue ,Watch} from 'vue-property-decorator';


@Component
export default class Tabla extends Vue {
  
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  private path:string='http://localhost/sobrecostos/api/';
  private search:string='';
  private valid_dialog:boolean=true;
  private usuarios: object[] = [];
  
  private editedIndex: number= -1;

  private dialog:boolean = false;

  private snack={
    color: '',
    text:'',
    show:false,
  };

  private editUser:any = [
    {
      id: "nombre",
      titulo: "Nombre",
      dato: "",
      tipo: "text",
      validacion: "required"
    },
    {
      id: "cedula",
      titulo: "Cedula",
      dato: "",
      tipo: "text",
      validacion: "required"
    },
    {
      id: "usuario",
      titulo: "Usuario",
      dato: "",
      tipo: "text",
      validacion: "required"
    },
    {
      id: "sede",
      titulo: "Sede",
      dato: "",
      tipo: "select",
      items: [],
      validacion: "required"
    },
    {
      id: "perfil",
      titulo: "Perfil Usuario",
      dato: "",
      tipo: "select",
      items: [],
      validacion: "required"
    },
    {
      id: "password",
      titulo: "Contraseña",
      dato: "",
      tipo: "password",
      validacion: "required"
    },
  ];

  
  private  id_usuario: number =0;
  
  private headers = [
    { text: 'Nombre', value: 'nom' },
    { text: 'Usuario ', value: 'user' },
    { text: 'Cedula', value: 'cc' },
    { text: 'Perfil', value: 'perfil' },
    { text: 'Sede', value: 'sede' },
    { text: 'modificar', value: 'mod' },
  ];

  private dictionary = {
    custom: {
      nombre: {
        required: () => "El nombre es requerido"
        // custom messages
      },
      cedula: {
        required: () => "La cedula es requerida"
      },
      usuario: {
        required: () => "El usuario es requerido"
      },
      sede: {
        required: () => "Seleccionar una sede"
      },
      perfil: {
        required: () => "Seleccionar un perfil",
      },
      password: {
        required: () => "La contraseña es requerida"
      },
    }
  };
  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  private created() {
    this.loadUsers();
    this.loadProfiles();
    this.loadSedes();
  }

  @Watch('dialog')
  onPropertyChanged(val: boolean, oldValue: string) {
    if(!val){
      this.id_usuario = 0;
      this.$validator.reset();
      this.editUser.forEach(function(part:string, index:string, theArray:any) {
        theArray[index].dato = "";
      });
    }
  }

  private mounted() {
    this.$validator.localize("es", this.dictionary);
  }

  get formTitle () {
    return this.id_usuario === 0 ? 'Nuevo Usuario' : 'Editar Usuario'
  }

  get filtered(){
    let filtereddata:object[]=[];
    let search=this.search.toLowerCase();
    this.usuarios.forEach(function(element:any){
      
      for(let i in element){
        if(String(element[i]).toLowerCase().search(search)!=-1){
            filtereddata.push(element);
            break;
        }
      }

    });
    return filtereddata;
  }

  private loadUsers(){
    this.search='';
    this.axios
      .get(this.path+'usuarios', {
        params: {
          perfil: localStorage.perfil
        }
      })
      .then((res) => {
        
        if(res.data) {
          if(res.data.estado) {
            this.usuarios=res.data.contenido;
          }
        }

      })
      .catch((error) => {
        // eslint-disable-next-line
        console.error(error);
      });
  }

  private loadProfiles(){
    
    this.axios.get(this.path+'perfiles', {
      params: {
        perfil: localStorage.perfil
      }
    })
    .then((res) => {
      
      if(res.data) {
        if(res.data.estado) {
          let items=new Array;
          res.data.contenido.forEach(function(x:any) {
            items.push({'id':x.id,'valor':x.perfil});
          });
          this.editUser[4].items=items;
        }
      }

    })
    .catch((error) => {
      // eslint-disable-next-line
      console.error(error);
    });
  }

  private loadSedes(){
    
    this.axios.get(this.path+'sedes', {
      params: {
        perfil: localStorage.perfil
      }
    })
    .then((res) => {
      
      if(res.data) {
        if(res.data.estado) {
          let items=new Array;
          res.data.contenido.forEach(function(x:any) {
            items.push({'id':x.codigo,'valor':x.descripcion});
          });
          this.editUser[3].items=items;
        }
      }

    })
    .catch((error) => {
      // eslint-disable-next-line
      console.error(error);
    });
  }

  private editItem (item:any) {
    this.id_usuario=item.id;
    this.editUser[0].dato=item.nombre;
    this.editUser[1].dato=item.cedula;
    this.editUser[2].dato=item.usuario;
    this.editUser[3].dato=item.sede;
    this.editUser[4].dato=item.perfil;
    
    // this.editUser = Object.assign({}, item)
    this.dialog = true
  }

  private save () {

    // const accion=(this.editedIndex === -1 ? 'nuevo' : 'editar');
    
    this.$validator.validateAll().then(result => {
      
      if(result){
        
        let newusuario:any = {};
        this.editUser.map(function(x:any) {
          newusuario[x.id]=x.dato;
        });
        newusuario['id_usuario'] = this.id_usuario;
               
        
        let formData = new FormData();
        formData.append('datosusuario', JSON.stringify(newusuario));
        
        this.axios
        .post(this.path + 'modusuario', formData, {
            headers: { 'Content-Type': 'multipart/form-data' },
        })
        .then((res) => {
          this.dialog = false;
          this.loadUsers();
        })
        .catch((error) => {
          // eslint-disable-next-line
          console.error(error);
        });

      }

    });
  }

}
</script>