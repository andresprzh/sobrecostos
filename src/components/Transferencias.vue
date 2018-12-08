<template>
  <v-container grid-list-md>
    
    <h1 class="font-weight-black font-italic text-xs-center" >Solicitud de transferencias</h1>
    <v-form>

      <v-layout row wrap>

        <v-flex xs12 > 

          <v-select 
            ref="menu"
            :items="plaremi.items"
            v-model="plaremi.dato"
            :label="plaremi.titulo"
            item-text="factura"
            item-value="factura"
            required
          ></v-select>

        </v-flex>

      </v-layout>


    </v-form>

    <v-card row wrap v-show="plaremi.dato">

      <v-card-title>
        ITEMS
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
          <td class="text-xs-left">{{props.item.descripcion}}</td>
          <td class="text-xs-left">{{props.item.cantidad}}</td>
          <td class="text-xs-left">{{props.item.sobrantes}}</td>
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
export default class Transferencia extends Vue {
  
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  private path:string="http://192.168.0.10/sobrecostos/api/";
  private search:string=' ';
  
  private items: object[] = [];
  
  private snack={
    color: "",
    text:"",
    show:false,
  };
  
  private plaremi={
    id: 'plaremi',
    titulo: 'Remision',
    dato: '',
    items: [],
  };
  
  private headers = [
    { text: "Descripcion", value: "desc" },
    { text: "Cantidad ", value: "unidad" },
    { text: "cantidad sobrante", value: "sobrante" },
    { text: "cantidad a pedir", value: "pedir" },
    { text: "sede", value: "sede" },
  ];
  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  constructor() {
    super();

    const path = this.path+'plaremi';

    this.axios
      .get(path, {
        params: {
          dato: 'all'
        }
      })
      .then(res => {
        console.log(res.data);
        if(res){
          
          this.plaremi.items=res.data;
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
      });
  }
}
</script>