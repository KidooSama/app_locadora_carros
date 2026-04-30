<template>
        <table class="table table-hover">
            <thead>
                <tr >
                    <th v-for="t,key in titulos" :key="key" scope="col">{{t.titulo}}</th>
                    <th v-if="visualizar.visivel || atualizar.visivel || remover"></th>
                </tr>
            </thead>
            <tbody>

                <tr v-for="obj,k in dadosFiltrados" :key="k">
                    <td v-for="d,chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">{{ d }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'img'"><img :src="'/storage/' + d" width="60"></span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">
                            {{d | formataDataTempoGlobal}}
                        </span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel"> 
                        <button v-if="visualizar.visivel"  class="btn btn-outline-primary btn-sm" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(obj)">Visualizar</button>
                        <button v-if="atualizar.visivel" class="btn btn-outline-primary btn-sm" :data-toggle="atualizar.dataToggle" :data-target="atualizar.dataTarget" @click="setStore(obj)">Atualizar</button>
                        <button v-if="remover.visivel" class="btn btn-outline-danger btn-sm" :data-toggle="remover.dataToggle" :data-target="remover.dataTarget" @click="setStore(obj)">Remover</button>
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
        methods:{
            setStore(obj){
                this.$store.state.transacao.mensagem = ''
                this.$store.state.transacao.status = ''
                this.$store.state.transacao.dados = ''
                this.$store.state.item = obj
            }
        },
       
    }
</script>
