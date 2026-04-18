<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr >
                    <th v-for="t,key in titulos" :key="key" scope="col">{{t.titulo}}</th>
                </tr>
            </thead>
            <tbody>

                <tr v-for="obj,k in dadosFiltrados" :key="k">
                    <td v-for="d,chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">{{ d }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'img'"><img :src="'/storage/' + d" width="40"></span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{'........'+ d }}</span>

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
        props:['marcas','titulos'],
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
