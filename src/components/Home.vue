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
              <span class="headline">Cargar consolidado periodicamente en el enlace <a href="#/upfile">SUBIR CONSOLIDADO</a></span>
              <p>- Para cargar el archivo es necesario guardarlo en formato CSV</p>
              <v-img
                :src="imagenes.DC[0]"
                max-width="720px"
              ></v-img>
              <p>- Tras guardar el archivo cagarlo en <a href="#/upfile">subir consolidado</a> arrastrando el archivo o dando click sobre el cuadro punteado y buscando el archivo </p>
              
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

              <p>- Cuando se tenga el archivo seleccionado en la pagina se presiona el boton de subir para cargar el archivo, cuando este termine de cargar la pagina mostrara un mensaje de que el proceso se ha completado
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
              <p>- El archivo se puede cargar arrastrando o dando click sobre el cuadro punteado y buscando el archivo </p>
                
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
              <span class="headline">Tras cargar el archivo de pla_remi ir al enlace<a href="#/upcopi">REDISTRIBUCION DE STOCKS</a> para comenzar a realizar transferencias</span>
              <p>- Seleccionar un plaremi para que se muestre los items sobrantes de las sedes </p>
              <v-img
                :src="imagenes.PV[2]"
                max-width="720px"
              ></v-img>
              
              <p>- para cargar items en una solicitud  de transferencia hay que marcar el item que se quiere pedir de la tabla</p>
              <small class="font-italic">* Al asignar una cantidad solicitada del item este se marca autonamticaente</small>
              <v-img
                :src="imagenes.PV[3]"
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
      ],  
    };

   items= [
          {
            action: 'fa-dollar-sign',
            title: 'Departamento de compras',
            items: [
              { title: 'Cargar consolidado periodicamente en el enlace ',
                ruta:'##/upfile',
                ruta_text:'SUBIR CONSOLIDADO'
              }
            ]
          },
          {
            action: 'fa-store-alt',
            title: 'Punto de venta',
            active: true,
            items: [
              { title: 'Para cada pedido a copidrogas cargar pla_remi en el enlace ',
                ruta:'#/upfile',
                ruta_text:'CARGAR PLA_REMI COPIDROGAS'
              },
              { title: 'Una vez cargado el plano se mostraran los productos con sobrestock en otras sedes.'},
              { title: 'Ir al enlace ',
                ruta:'#/remisiones',
                ruta_text:'REDISTRIBUCION DE STOCKS'
              },
              { title: 'Escoger la factura copidrogas que esta procesando' },
              { title: 'En la tabla puede observar los productos con sobrestock en otras sedes. puede filtrar,reordenar por sede o por producto.' },
              { title: 'En el campo buscar puede buscar por item, descripcion o sede' },
              { title: 'Puedo cambia la cantidad a solicitar danbdo click sobre ella' },
              { title: 'Al darle al boton siguiente pregunta si queremos solicitar transferencia.se debe realizar una sola solicitud por cada sede con sobrestock' },
              { title: 'Registrar la solicitud en sistema uno e ingresarla en cada solicitud.' },
              { title: 'Puedo verificar las transferencias solicitadas en el enlace verificar transferencias. desde este enlace se puede generar un nuevo pla_remi modificado que ya se puede cargar a la pagina de copidrogas',
                ruta:'#/transferencias',
                ruta_text:'VERIFICAR TRANSFERENCIAS'
              },
            ]
          },
        ]

  }
</script>

<style scoped>

</style>