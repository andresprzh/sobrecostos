<template>
  <div style="padding:10px;">
    
    <h1 class="display-3 text-xs-center">Instrucciones de uso</h1>
    

    <v-list>
      <!-- ==================================================================================== 
                                      DEPARTAMENTO DE COMPRA
      ======================================================================================-->
      <v-list-group >
        <v-list-tile slot="activator">
          <v-list-tile-content>
            <v-list-tile-title>
              <v-icon>fa-dollar-sign</v-icon> Departamento de compras
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <div class="list-group">
          <div style="padding:20px;" >
            <v-card color="grey lighten-5" style="padding:10px" class="black--text">
              <v-card-title primary-title>
                <div>
                  <span class="headline">Cargar consolidado periodicamente en el enlace <a href="#/upfile">SUBIR CONSOLIDADO</a></span>
                  <p><v-icon style="font-size:15px">fa-circle</v-icon> Para cargar el archivo es necesario guardarlo en formato CSV</p>
                  <v-img
                    :src="imagenes.DC[0]"
                    max-width="1080px"
                  ></v-img>
                  <p><v-icon style="font-size:15px">fa-circle</v-icon> Tras guardar el archivo cagarlo en <a href="#/upfile">subir consolidado</a> arrastrando el archivo o dando click sobre el cuadro punteado y buscando el archivo </p>
                  
                  <v-layout row>
                    <v-flex xs12 md6  >
                    <v-img
                      :src="imagenes.DC[1]"

                    ></v-img>
                    </v-flex>
                    <v-flex xs12 md6 >
                    <v-img
                      :src="imagenes.DC[2]"

                    ></v-img>
                    </v-flex>
                  </v-layout>

                  <p><v-icon style="font-size:15px">fa-circle</v-icon> Cuando se tenga el archivo seleccionado en la pagina se presiona el boton de subir para cargar el archivo, cuando este termine de cargar la pagina mostrara un mensaje de que el proceso se ha completado
                  <br>
                  <small class="font-italic">* El proceso puede tardar varios segundos dependiendo del tamaño del archivo</small>
                  </p>
                  <v-img
                    :src="imagenes.DC[3]"
                    max-width="1080px"
                  ></v-img>
                  
                </div>
              </v-card-title>
              
              <v-card-actions></v-card-actions>
            </v-card>
          </div >
        </div>
      </v-list-group>
      <!-- ==================================================================================== 
                                      PUNTO DE VENTA
      ======================================================================================-->
      <v-list-group >
        
        <v-list-tile slot="activator">
          <v-list-tile-content>
            <v-list-tile-title>
              <v-icon>fa-store-alt</v-icon> Punto de venta
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <div class="list-group">
          <div style="padding:20px;" >
            <v-card color="grey lighten-5" style="padding:10px" class="black--text">
              <v-card-title primary-title>
                <div>
                  <span class="headline">El archivo planor de pla_remi puede ser seleccionado y cargado desde el enlace <a href="#/upcopi">CARGAR PLA_REMI COPIDROGAS</a></span>
                  <p><v-icon style="font-size:15px">fa-circle</v-icon> El archivo se puede cargar arrastrando o dando click sobre el cuadro punteado y buscando el archivo</p>
                    
                  <v-layout row>
                      <v-flex xs12 md6  >
                      <v-img
                        :src="imagenes.PV[0]"

                      ></v-img>
                      </v-flex>
                      <v-flex xs12 md6 >
                      <v-img
                        :src="imagenes.PV[1]"

                      ></v-img>
                      </v-flex>
                  </v-layout>
                </div>
              </v-card-title>
              
              <v-card-actions></v-card-actions>
            </v-card>
            <br>
            <v-card color="grey lighten-5" style="padding:10px" class="black--text">
              <v-card-title primary-title>
                <div>
                  <span class="headline">Tras cargar el archivo de pla_remi ir al enlace<a href="#/upcopi">REDISTRIBUCION DE STOCKS</a> para comenzar a realizar transferencias</span>
                  <p><v-icon style="font-size:15px">fa-circle</v-icon> Seleccionar un plaremi para que se muestre los items sobrantes de las sedes </p>
                  <v-img
                    :src="imagenes.PV[2]"
                    max-width="1080px"
                  ></v-img>
                  
                  <p><v-icon style="font-size:15px">fa-circle</v-icon> para cargar items en una solicitud  de transferencia hay que marcar el item que se quiere pedir de la tabla
                  <small class="font-italic">* Al asignar una cantidad solicitada del item este se marca autonamticaente</small>
                  </p>
                  <v-img
                    :src="imagenes.PV[3]"
                    max-width="1080px"
                  ></v-img>

                  <p><v-icon style="font-size:15px">fa-circle</v-icon> Cuando ya se tengan marcados todos los items a los que  se le quiere realizra transferencia, se da click en el boton siguiente, que abrira una pagina dondes se puede verificar,eliminar, modificar y asignar el numero de transferencia
                  <br>
                  <small class="font-italic">* solo puede haber 1 transferencia por sede</small>
                  </p>
                  <v-img
                    :src="imagenes.PV[4]"
                    max-width="1080px"
                  ></v-img> 

                  <p><v-icon style="font-size:15px">fa-circle</v-icon> Cuando se llenen correctamente todos los datos es posible registrar la transferencia con el boboton  registrar transferecnia
                  <br>
                  <small class="font-italic">* el numero de de transferencia no puede repetirse, no se pueden solicitra mas  items de los sobrantes</small>
                  </p>
                  <v-layout row>
                      <v-flex xs12 md6  >
                      <v-img
                        :src="imagenes.PV[5]"

                      ></v-img>
                      </v-flex>
                      <v-flex xs12 md6 >
                      <v-img
                        :src="imagenes.PV[6]"

                      ></v-img>
                      </v-flex>
                  </v-layout>
                </div>
              </v-card-title>
              
              <v-card-actions></v-card-actions>
            </v-card>
            <br>
            <v-card color="grey lighten-5" style="padding:10px" class="black--text">
              <v-card-title primary-title>
                <div>
                  <span class="headline">Generar nuevo pla_remi y ver transferencias en el enlace <a href="#/remisiones">VERIFICAR TRANSFERENCIAS</a></span>
                  <p><v-icon style="font-size:15px">fa-circle</v-icon> Tras crear las transferencias es posibl verlas desde el enlace de <a href="#/remisiones">VERIFICAR TRANSFERENCIAS</a> donde se seleccionara la factura del pla_remi especifico y se podran observar las diferentes transferencias con su orignes y los items a transferir</p>
                    <v-img
                        :src="imagenes.PV[7]"
                        max-width="1080px"
                    ></v-img>
                  
                  <p><v-icon style="font-size:15px">fa-circle</v-icon> para generar el nuevo pla_remi solo basta con hacre click en el boton de "GENERAR ARCHIVO DE PLA_REMI MODIFICADO" <a href="#/remisiones">VERIFICAR TRANSFERENCIAS</a> donde se seleccionara la factura del pla_remi especifico y se podran observar las diferentes transferencias con su orignes y los items a transferir</p>
                    <v-img
                        :src="imagenes.PV[8]"
                        max-width="1080px"
                    ></v-img>
                </div>
              </v-card-title>
              
              <v-card-actions></v-card-actions>
            </v-card>
          </div>
        </div>
      </v-list-group>
      
    </v-list>    

  </div >
</template>

<style scoped>
  .list-group{
    max-height: 70vh;
    overflow-y: scroll;
  }
  p{
    font-size: 20px;
  }
</style>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";

@Component
export default class Home extends Vue {
  /*===========================================================================================================
                                          ATRIBUTOS
  =============================================================================================================*/
  private imagenes= {
    DC:[
        require("@/assets/instrucciones/DC0.png"),
        require("@/assets/instrucciones/DC1.png"),
        require("@/assets/instrucciones/DC2.png"),
        require("@/assets/instrucciones/DC3.png"),
      ],
    PV:[
       require("@/assets/instrucciones/PV0.png"),
       require("@/assets/instrucciones/PV1.png"),
       require("@/assets/instrucciones/PV2.png"),
       require("@/assets/instrucciones/PV3.png"),
       require("@/assets/instrucciones/PV4.png"),
       require("@/assets/instrucciones/PV5.png"),
       require("@/assets/instrucciones/PV6.png"),
       require("@/assets/instrucciones/PV7.png"),
       require("@/assets/instrucciones/PV8.png"),
      ],  
    };
    
  }
</script>

