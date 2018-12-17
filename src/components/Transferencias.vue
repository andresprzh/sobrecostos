<template >
    <v-container fluid grid-list-md>
      <h2 class="display-3 text-xs-center">Solicitudes de transferencia</h2>
      <v-layout row wrap>

        <v-flex xs12 > 

          <v-select 
            ref="menu"
            :items="plaremi.items"
            v-model="plaremi.dato"
            :label="plaremi.titulo"
            item-text="factura"
            item-value="factura"
            @change="selplaremi"
            required
          ></v-select>

        </v-flex>

      </v-layout>
    
    <div v-show="showdata">
      <v-btn  block color="secondary" @click="generardoc" >
        <span class="mx-2">Generar archivo</span>
        <v-icon left>fa-file-alt</v-icon>
      </v-btn>
      <v-data-iterator 
        :items="transferencias"
        :rows-per-page-items="rowsPerPageItems"
        :pagination.sync="pagination"
        content-tag="v-layout"
        row
        wrap
      > 
        <v-flex
          slot="item"
          slot-scope="props"
          xs12
          sm6
          md4
          lg3
        >
          <v-card class="transferencias" >
            <div style="height: 30%;">
            <v-card-title class="py-1 secondary--text"><h4>{{ props.item.no_transferencia }}</h4></v-card-title>
            <v-card-title class="py-1 info--text"><h5>{{ props.item.origensede }}</h5></v-card-title>
            <v-divider></v-divider>
            </div>
            <v-list class="transitems" dense>
              <v-list-tile  v-for="(itemst,index) in props.item.items" :key="index">
                <v-list-tile-content>{{itemst.descripcion}}:</v-list-tile-content>
                <v-list-tile-content class="align-end info--text">{{itemst.pedido}}</v-list-tile-content>
              </v-list-tile>
            </v-list>
          </v-card>
        </v-flex>
      </v-data-iterator>
    </div>
  </v-container>
</template>
<style scoped>
  .transferencias{
    height:200px ;
    /* overflow-y: auto; */
  }
  .transitems{
    height: 55%;
    overflow-y: auto;
  }
</style>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import App from '@/App.vue';

@Component
export default class Solicitudes extends App {
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
    private showdata:boolean=false;
    protected plaremi={
    id: 'plaremi',
    titulo: 'Remision',
    dato: '',
    items: [],
    };
  
    rowsPerPageItems=[4, 6, 12];
    pagination= {
        rowsPerPage: 6
    };
    private transferencias=[];
   items= [
        {
          value: false,
          name: 'Frozen Yogurt',
          calories: 159,
          fat: 6.0,
          carbs: 24,
          protein: 4.0,
          sodium: 87,
          calcium: '14%',
          iron: '1%'
        },
        {
          value: false,
          name: 'Ice cream sandwich',
          calories: 237,
          fat: 9.0,
          carbs: 37,
          protein: 4.3,
          sodium: 129,
          calcium: '8%',
          iron: '1%'
        },
        {
          value: false,
          name: 'Eclair',
          calories: 262,
          fat: 16.0,
          carbs: 23,
          protein: 6.0,
          sodium: 337,
          calcium: '6%',
          iron: '7%'
        },
        {
          value: false,
          name: 'Cupcake',
          calories: 305,
          fat: 3.7,
          carbs: 67,
          protein: 4.3,
          sodium: 413,
          calcium: '3%',
          iron: '8%'
        },
        {
          value: false,
          name: 'Gingerbread',
          calories: 356,
          fat: 16.0,
          carbs: 49,
          protein: 3.9,
          sodium: 327,
          calcium: '7%',
          iron: '16%'
        },
        {
          value: false,
          name: 'Jelly bean',
          calories: 375,
          fat: 0.0,
          carbs: 94,
          protein: 0.0,
          sodium: 50,
          calcium: '0%',
          iron: '0%'
        },
        {
          value: false,
          name: 'Lollipop',
          calories: 392,
          fat: 0.2,
          carbs: 98,
          protein: 0,
          sodium: 38,
          calcium: '0%',
          iron: '2%'
        },
        {
          value: false,
          name: 'Honeycomb',
          calories: 408,
          fat: 3.2,
          carbs: 87,
          protein: 6.5,
          sodium: 562,
          calcium: '0%',
          iron: '45%'
        },
        {
          value: false,
          name: 'Donut',
          calories: 452,
          fat: 25.0,
          carbs: 51,
          protein: 4.9,
          sodium: 326,
          calcium: '2%',
          iron: '22%'
        },
        {
          value: false,
          name: 'KitKat',
          calories: 518,
          fat: 26.0,
          carbs: 65,
          protein: 7,
          sodium: 54,
          calcium: '12%',
          iron: '6%'
        }
    ];

   /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/
  private created() {

    const path = this.path+'plaremi';
    this.axios
      .get(path, {
        params: {
          sede: localStorage.sede
        }
      })
      .then(res => {
        
        if(res.data){
          
          this.plaremi.items=res.data;
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
    });
  }

  protected selplaremi(){

    const path = this.path+'transferencia';
    this.axios
      .get(path, {
        params: {
          encargado: localStorage.id,
          plaremi:this.plaremi.dato,
        }
      })
      .then(res => {
        if(res.data){
          this.showdata=true, 
          this.transferencias=Object.values(res.data);
        }else{
          
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
    });
   

  }

  private generardoc(){
    const path = this.path+'plaremidoc';
    this.axios
      .get(path, {
        params: {
          plaremi: this.plaremi.dato
        }
      })
      .then(res => {
        if(res.data){
          let no_rem = ('000' + res.data.plaremi).slice(-3);
          let nomdoc = `REMIS${no_rem}.RM0`;

          let element = document.createElement('a');
          element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(res.data.documento));
          element.setAttribute('download', nomdoc);

          element.style.display = 'none';
          document.body.appendChild(element);

          element.click();

          document.body.removeChild(element);

        }else{
          
        }

      })
      .catch(error => {
        // eslint-disable-next-line
        console.error(error);
    });
  }
}
</script>

