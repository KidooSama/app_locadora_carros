<template>
    <div>
        
        <table class="table table-hover">
            <thead>
                <tr >
                    <th v-for="t,key in titulos" :key="key" scope="col">{{t.titulo}}</th>
                    <th v-if="visualizar.visivel || atualizar || remover"></th>
                </tr>
            </thead>
            <tbody>

                <tr v-for="obj,k in dadosFiltrados" :key="k">
                    <td v-for="d,chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">{{ d }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'img'"><img :src="'/storage/' + d" width="40"></span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{''+ d }}</span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar || remover"> 
                        <button v-if="visualizar"  class="btn btn-outline-primary btn-sm" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget">Visualizar</button>
                        <button v-if="atualizar" class="btn btn-outline-primary btn-sm">Atualizar</button>
                        <button v-if="remover" class="btn btn-outline-danger btn-sm">Remover</button>
                    </td>
                </tr>                         
                
                <!-- <tr v-for="obj in marcas" :key="obj.id">
                    <td v-for="coluna in titulos" :key="coluna.col">
                        <span v-if="coluna.tipo !== 'imagem'">
                            {{ obj[coluna.col] }}
                        </span>
                        <img 
                            v-else 
                            :src="'/storage/' + obj[coluna.col]" 
                            width="40"
                        >
                    </td>
                </tr> -->

            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props:['marcas','titulos','visualizar','atualizar','remover'],
        computed:{
            dadosFiltrados(){
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []

                this.marcas.map((item, chave)=>{
                    let itemFiltrado = {}
                    campos.forEach(campo =>{
                        itemFiltrado[campo] = item[campo]

                    })
                    dadosFiltrados.push(itemFiltrado)

                })
                
                return dadosFiltrados
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
