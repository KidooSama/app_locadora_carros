<template>
        <table class="table table-hover">
            <thead>
                <tr >
                    <th v-for="t,key in titulos" :key="key" scope="col">{{t.titulo}}</th>
                    <th v-if="visualizar.visivel || atualizar.visivel || remover"> Ação </th>
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
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle`" type="button" data-toggle="dropdown" aria-expanded="false">
                                Alterar dados
                            </button>
                            <div class="dropdown-menu">
                                <button v-if="visualizar.visivel" class="dropdown-item " type="button" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(obj)">Visualizar</button>
                                <button v-if="atualizar.visivel" class="dropdown-item" type="button" :data-toggle="atualizar.dataToggle" :data-target="atualizar.dataTarget" @click="setStore(obj)">Atualizar</button>
                                <button v-if="remover.visivel" class="dropdown-item" type="button" :data-toggle="remover.dataToggle" :data-target="remover.dataTarget" @click="setStore(obj)">Remover</button>
                            </div>
                        </div>
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
        
        props:['dados','titulos','visualizar','atualizar','remover'],
        computed:{
            dadosFiltrados(){
                let campos = Object.keys(this.titulos)
                // Forma mais limpa com map
                let dadosFiltrados = this.dados.map(item => {
                    let itemFiltrado = {}

                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo]
                    })

                    return itemFiltrado  // map usa o return pra montar o array novo
                })
                console.log(dadosFiltrados)
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
