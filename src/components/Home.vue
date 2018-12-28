<template>
  <div style="padding:10px;">
    
    <h1 class="display-3 text-xs-center">Instrucciones de uso</h1>
    

    <v-list>
      <!-- ==================================================================================== 
                                      DEPARTAMENTO DE COMPRA
      ======================================================================================-->
      <v-list-group>
        <v-list-tile slot="activator">
          <v-list-tile-content>
            <v-list-tile-title>
              <v-icon>fa-dollar-sign</v-icon> Departamento de compras
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-card color="grey lighten-5" style="padding:10px" class="black--text">
          <v-card-title primary-title>
            <div>
              <span class="headline">Cargar consolidado periódicamente en el enlace <a href="#/upfile">SUBIR CONSOLIDADO</a></span>
              <p><v-icon style="color:#0277BD;">fa-circle</v-icon> Para cargar el archivo es necesario guardarlo en formato CSV</p>
              <v-img
                :src="imagenes.DC[0]"
                max-width="720px"
              ></v-img>
              <p><v-icon style="color:#0277BD;">fa-circle</v-icon> Tras guardar el archivo cargarlo en <a href="#/upfile">subir consolidado</a> arrastrando el archivo o dando click sobre el cuadro punteado y buscando el archivo </p>
              
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

              <p><v-icon style="color:#0277BD;">fa-circle</v-icon> Cuando se tenga el archivo seleccionado en la página se presiona el botón de subir para cargar el archivo, cuando este termine de cargar la página mostrará un mensaje de que el proceso se ha completado
              </br>
              <small class="font-italic">* El proceso puede tardar varios segundos dependiendo del tamaño del archivo</small>
              </p>
              <v-img
                :src="imagenes.DC[3]"
                max-width="720px"
              ></v-img>
              
            </div>
          </v-card-title>
          
          <v-card-actions></v-card-actions>
        </v-card>
      </v-list-group>
      <!-- ==================================================================================== 
                                      PUNTO DE VENTA
      ======================================================================================-->
      <v-list-group>
        <v-list-tile slot="activator">
          <v-list-tile-content>
            <v-list-tile-title>
              <v-icon>fa-store-alt</v-icon> Punto de venta
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>

        <v-card color="grey lighten-5" style="padding:10px" class="black--text">
          <v-card-title primary-title>
            <div>
              <span class="headline">Cargar pla_remi en el enlace <a href="#/upcopi">CARGAR PLA_REMI COPIDROGAS</a></span>
              <p><v-icon style="color:#0277BD;">fa-circle</v-icon> El archivo se puede cargar arrastrando o dando click sobre el cuadro punteado y buscando el archivo </p>
                
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

        <v-card color="grey lighten-5" style="padding:10px" class="black--text">
          <v-card-title primary-title>
            <div>
              <span class="headline">Tras cargar el archivo de pla_remi ir al enlace<a href="#/upcopi">REDISTRIBUCIÓN DE STOCKS</a> para comenzar a realizar transferencias</span>
              <p><v-icon style="color:#0277BD;">fa-circle</v-icon> Seleccionar un plaremi para que se muestre los items sobrantes de las sedes </p>
              <v-img
                :src="imagenes.PV[2]"
                max-width="720px"
              ></v-img>
              
              <p><v-icon style="color:#0277BD;">fa-circle</v-icon> Para cargar ítems en una solicitud  de transferencia hay que marcar el ítem que se quiere pedir de la tabla
              <small class="font-italic">* Al asignar una cantidad solicitada del ítem este se marca automáticamente</small>
              </p>
              <v-img
                :src="imagenes.PV[3]"
                max-width="720px"
              ></v-img>

              <p><v-icon style="color:#0277BD;">fa-circle</v-icon> Cuando ya se tengan marcados todos los ítems a los que  se le quiere realizar transferencia, se da click en el botón siguiente, que abrirá una página dondes se puede verificar,eliminar, modificar y asignar el número de transferencia
              </br>
              <small class="font-italic">* Solo puede haber 1 transferencia por sede</small>
              </p>
              <v-img
                :src="imagenes.PV[4]"
                max-width="720px"
              ></v-img>

              <p><v-icon style="color:#0277BD;">fa-circle</v-icon> Cuando se llenen correctamente todos los datos es posible registrar la transferencia con el botón  registrar transferencia
              </br>
              <small class="font-italic">* El número de de transferencia no puede repetirse, no se pueden solicitar más  ítems de los sobrantes</small>
              </p>
              <v-img
                :src="imagenes.PV[5]"
                max-width="720px"
              ></v-img>
            </div>
          </v-card-title>
          
          <v-card-actions></v-card-actions>
          
        </v-card>

      </v-list-group>
      
    </v-list>    

  </div >
</template>

<style scoped>
  p{
    font-size:18px;
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
       require("@/assets/instrucciones/PV4.png"),,
      ],  
    };

  /*===========================================================================================================
                                          METODOS
  =============================================================================================================*/

  }
</script>

