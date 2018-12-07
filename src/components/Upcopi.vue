<template>
  <v-container grid-list-md>
    
    <h1 class="font-weight-black font-italic text-xs-center" >Titulo</h1>
    <!-- **************************************************************************************************************** 
                                            INPUTS FORMULARIO
    **************************************************************************************************************** -->
    <v-form ref="form"  :v-model="valid" v-show="!mostrartabla" >
    
      <v-layout row wrap>

        <v-flex xs12 row wrap class="file-upload"  >
          
          <v-flex xs12 v-show="true" class="file-upload-main"  justify-center>

              <input type="file" name="file" id="file"  @change="processFile($event)"/>
              <label for="file" >

                <v-icon v-if="file.name==null">fa-upload</v-icon>
                {{file.name}}

              </label>

          </v-flex>

          <v-flex xs12>

            <v-alert :value="!valid && !file.name" type="error">Seleccionar un archivo.</v-alert>

          </v-flex>

        </v-flex >

        <v-btn block color="secondary"@click="submit_file">Subir</v-btn>

      </v-layout>

    </v-form> 

    <v-card row wrap v-show="mostrartabla">

      <v-card-title class="justify-center">

        <span class="secondary--text text-xs-center" style="font-size:200%;">Items encontrados</span><br>

      </v-card-title>
    
      <v-data-table
        :headers="headers"
        :items="items"
        select-all
        item-key="name"
        class="elevation-1"
      >
        <template slot="headers" slot-scope="props">

          <tr>
          
            <th>

              <v-checkbox :input-value="props.all" :indeterminate="props.indeterminate" primary hide-details></v-checkbox>

            </th>
            <th v-for="header in props.headers" :key="header.text">

              <v-icon small>fas fa-arrows-alt-v</v-icon>
              {{ header.text }}

            </th>

          </tr>

        </template> 

        <template slot="items" slot-scope="props">

        <tr >

          <td >

            <v-checkbox v-model="props.item.selected" primary hide-details ></v-checkbox>

          </td>
          <td class="text-xs-left">{{ props.item.descripcion }}</td>
          <td class="text-xs-left">{{ props.item.precio_unidad }}</td>
          <td class="text-xs-left">{{ props.item.cantidad }}</td>
          <td class="text-xs-left">{{ props.item.sobrantes }}</td>
          <td class="text-xs-right">

            <v-edit-dialog
              :return-value.sync="props.item.pedido"
              large lazy persistent
              @save="props.item.selected=true"
            >
              <div>{{ props.item.pedido }}</div>
              <div slot="input" class="mt-3 title">Update pedido</div>
              <v-text-field
                slot="input"
                v-model="props.item.pedido"
                label="Edit"
                single-line
                counter
                autofocus
              ></v-text-field>

            </v-edit-dialog>

          </td>
          <td class="text-xs-left">{{ props.item.sedesobrante }}</td>

        </tr>
        </template>

      </v-data-table>
      <v-snackbar v-model="snack.show" :timeout="3000" :color="snack.color">
        {{ snack.text }}
        <v-btn flat @click="snack.show = false">Close</v-btn>
      </v-snackbar>

      <div class="text-xs-right pt-2">

        <v-btn color="secondary" @click="transladar">

          <v-icon>fa-save</v-icon>
          <span>Guardar</span>

        </v-btn>

      </div>
      
    </v-card> 

    
    <v-layout row justify-center>

        
        <v-dialog v-model="modal" persistent >

          <v-card>

            <v-form ref="form_modal"  :v-model="valid_modal" @submit="submit_modal">

              <v-card-title class="headline">Transladar Items</v-card-title>

              <v-card-text>
              
                <v-layout row wrap>

                  <v-flex xs12 v-for="(card,index) in entradas" :key="card.id"> 
                    <v-select v-if="card.tipo==='select'"
                      ref="menu"
                      v-validate="card.validacion"
                      :items="card.items"
                      item-text="descripcion"
                      item-value="codigo"
                      v-model="card.dato"
                      :error-messages="errors.collect(card.id)"
                      label="Sede"
                      :data-vv-name="card.id"
                      required
                    ></v-select>

                    <v-text-field v-else
                    ref="menu"
                    v-model="card.dato"
                    v-validate="card.validacion"
                    :error-messages="errors.collect(card.id)"
                    :label="card.titulo"
                    :data-vv-name="card.id "
                    required
                    ></v-text-field>

                  </v-flex>

                </v-layout>

              </v-card-text>

              <v-card-actions>

                <v-spacer></v-spacer>
                <v-btn color="green darken-1" flat @click="submit_modal">Aceptar</v-btn>
                <v-btn color="green darken-1" flat @click="modal = false">Cancelar</v-btn>

              </v-card-actions>

            </v-form>

          </v-card>

        </v-dialog>

      </v-layout>
    <!-- **************************************************************************************************************** 
                                            INPUTS FORMULARIO
    **************************************************************************************************************** -->
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";


@Component
export default class Upcopi extends Vue {
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  private file: any = {};
  
  private file_valid:boolean=true;
  private valid:boolean= true;
  protected loading:boolean=false;
  private modal:boolean=false;
  private valid_modal:boolean=true;
  private mostrartabla: boolean = false;
  
  private filename: string = "Subir archivo";
  private items: object[] = [];
  private itemssend=[];
  private selected= [];
  
  private snack={
    color: "",
    text:"",
    show:false,
  };

  // private entradas: object [];
  private entradas = [
    {
      id: "drog",
      titulo: "Codigo Drogueria",
      dato: "",
      tipo: "select",
      items: [],
      validacion: "required"
    },
    {
      id: "nombre",
      titulo: "Nombre",
      dato: "",
      tipo: "texto",
      validacion: "required"
    },
  ];

  private headers = [
    { text: "Descripcion", value: "desc" },
    { text: "Precio unidad", value: "precio" },
    { text: "Cantidad ", value: "unidad" },
    { text: "cantidad sobrante", value: "sobrante" },
    { text: "cantidad a pedir", value: "sobrante" },
    { text: "sede", value: "sede" },
  ];
 
  // Mensajes custom error vee validate
  private dictionary = {
    custom: {
      dia: {
        required: () => "Por favor seleccione un dia "
        // custom messages
      },
      drog: {
        required: () => "Por favor seleccione una sede"
      },
      file: {
        required: () => "Por favor seleccionar un archivo."
      },
      nombre: {
        required: () => "Por favor digite el nombre"
      },
      codcompra: {
        required: () => "por favor digite el codigo de compra",
        max: "Maximo 4 caracteres"
      },
      ftramite: {
        required: () => "Por favor seleccione una fecha"
      },
      nit: {
        required: () => "Por favor digite el NIT"
      }
    }
  };

  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  constructor() {
    super();

    const path = "http://localhost/sobrecostos/puntosv";

    this.axios
      .get(path, {
        params: {
          dato: "all"
        }
      })
      .then(res => {

        if(res){
          
          this.entradas[0].items=res.data;
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
      });
  }

  private mounted() {
    this.$validator.localize("es", this.dictionary);
  }

  private processFile(event: any) {

    if( event.target.files[0]){
      this.file = event.target.files[0];
      this.valid=true;
    }
    
  }

  private submit_file(event: any) {
     
    if (this.file.name) {
        this.valid=true;
        let formData = new FormData();
        formData.append("archivo", this.file);

        const path = "http://localhost/sobrecostos/copiupload";

        this.axios
          .post(path, formData, {
            headers: { "Content-Type": "multipart/form-data" }
          })
          .then(res => {
            console.log(res.data);

            if (res.data) {
              if(res.data.estado){
                this.items = res.data.contenido;
                this.mostrartabla=true;
              }else{
                alert(res.data.contenido);
              }
            } else {
              alert("error al subir el arcivo");
            }
          })
          .catch(error => {
            console.error(error);
          });
    }else{
      this.valid=false
    }
    
  }

  private transladar(event: any){
    // console.log(this.items);
    let itemssend=[];
    
    for(let i=0;i<this.items.length;i++){
      if(this.items[i].selected){

        const items={
                        'iditem': this.items[i].id_item,
                        'pedido': this.items[i].pedido,
                        'sede':this.items[i].sedesobrante,
        };
        itemssend.push(Object.values(items));
        
        
      }
    }
    
    // this.items.forEach(function(element:any) {
      
    //   if(element.selected){
    //     itemssend[cont]=({
    //                     'iditem': element.id_item,
    //                     'pedido': element.pedido,
    //                     'sede':element.sedesobrante,
    //                 });
        
    //     cont++;
    //   }
      
    // });
    console.log(itemssend);
    
    if (itemssend.length>0){
      this.itemssend=itemssend;
      this.modal=true;
    }else{
      // console.log(this.snack)
      this.snack.show=true;
      this.snack.color="error";
      this.snack.text="Seleccionar al menos 1 item";
    }
  }

  private submit_modal(event:any){
     this.$validator.validate().then(result => {
      
      if(result){
        
        let formData = new FormData();
        formData.append("items", this.itemssend);
        formData.append("sede", this.entradas[0].dato);
        formData.append("encargado", this.entradas[1].dato);

        const path = "http://localhost/sobrecostos/transladar";

        this.axios
          .post(path, formData, {
            headers: { "Content-Type": "multipart/form-data" }
          })
          .then(res => {
            console.log(res.data);

            if (res.data) {
              
              alert("Transferencia creada exitosamente");
              
            } else {

              alert("error al crear la transferencia");

            }
          })
          .catch(error => {
            console.error(error);
          });

        this.modal=false;

      }
     });
     
  }
  

}
</script>