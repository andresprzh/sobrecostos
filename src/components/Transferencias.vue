<template>
  <v-container grid-list-md>

      <v-form>
        <v-card row wrap  v-for="(itemsede,index) in items" :key="itemsede.id">

          <v-card-title>
            Transferencia {{itemsede[0].origensede}}
            <v-spacer></v-spacer>
            <v-text-field
              label="Numero de Transferencia"
            ></v-text-field>
          </v-card-title>
          
          <v-data-table
            :headers="headers"
            :items="itemsede"
            class="elevation-1">
            
            <template slot="items" slot-scope="props">
              <td class="text-xs-left">{{props.item.item}}</td>
              <td class="text-xs-left">{{props.item.sobrante}}</td>
              <td class="text-xs-left">{{props.item.solicitado}}</td>
              <td class="text-xs-left">
                <v-text-field
                  v-model="props.item.pedido"
                  label="Pedidos"
                  required
                ></v-text-field>
              </td>
            </template>
          </v-data-table>

          
        </v-card >

        <div class="text-xs-right pt-2">

          <v-btn color="secondary"  >
          
            <v-icon>fa-save</v-icon>
            <span>Guardar</span>

          </v-btn>

        </div>

      </v-form>

  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import App from '@/App.vue';

@Component
export default class Transferencia extends App {
  
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  private items: object[] = [];

  private id:string='';

  private plaremi={
    id: 'plaremi',
    titulo: 'Remision',
    dato: '',
    items: [],
  };
  
  private headers = [
    { text: 'Item', value: 'item' },
    { text: 'Sobrantes ', value: 'sob' },
    { text: 'Solicitados', value: 'col' },
    { text: 'Pedido', value: 'perfil' },
  ];

  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  private created() {
    this.id = this.$route.params.id; 
    const path = this.path+'transferencia';
    this.axios
      .get(path, {
        params: {
          encargado: localStorage.id,
          plaremi:this.id,
        }
      })
      .then(res => {
        if(res.data){
          console.log(res.data);
          this.items=res.data;
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
    });
  }


  private selplaremi(){

    this.$router.push({ name: 'Tabla', params: { id: this.plaremi.dato }})

  }
  
}

</script>