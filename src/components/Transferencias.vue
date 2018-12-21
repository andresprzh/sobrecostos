<template >
    <v-container fluid grid-list-md>
      <h2 class="display-3 text-xs-center">Transferencias solicitadas</h2>
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
        <span class="mx-2">Generar archivo plaremi modificado</span>
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
    titulo: 'Numero de factura copidrogas',
    dato: '',
    items: [],
    };
  
    rowsPerPageItems=[4, 6, 12];
    pagination= {
        rowsPerPage: 6
    };
    private transferencias=[];

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

