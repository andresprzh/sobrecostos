<template>
  <div style="padding:10px;">
    
    <h1 class="display-3 text-xs-center">Instrucciones de uso</h1>
    <!-- INPUTS FORMULARIO -->

          <v-list>
            <v-list-group
              v-for="item in items"
              v-model="item.active"
              :key="item.title"
              :prepend-icon="item.action"
              no-action
            >
              <v-list-tile slot="activator">
                <v-list-tile-content>
                  <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                </v-list-tile-content>
              </v-list-tile>

              <v-list-tile
                v-for="subItem in item.items"
                :key="subItem.title"
                
              >
                <v-list-tile-content>
                  <p>
                    <span>{{ subItem.title }}</span>
                    <a :href="subItem.ruta" >{{subItem.ruta_text}}</a>
                  </p>
                </v-list-tile-content>

                <v-list-tile-action>
                  <v-icon>{{ subItem.action }}</v-icon>
                </v-list-tile-action>
              </v-list-tile>
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