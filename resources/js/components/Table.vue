<template>
        <table class="table table-hover  align-middle text-center">
            <thead>
                <tr >
                    <th v-for="t,key in titulos" :key="key" scope="col">{{t.titulo}}</th>
                    <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel"> Ação </th>
                </tr>
            </thead>
            <tbody>

                <tr  v-for="obj,k in dadosFiltrados" :key="k">
                    <td v-for="d,chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">{{ d }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'fk'">{{ d.nome }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'bool'">{{ d ? 'Sim' : 'Não' }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'img'"><img :src="'/storage/' + d" width="60"></span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{d | formataDataTempoGlobal}}</span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel">
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle`" type="button" data-toggle="dropdown" aria-expanded="false">
                                Alterar dados
                            </button>
                            <div class="dropdown-menu">
                                <button v-if="visualizar.visivel" class="dropdown-item " type="button" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(obj.id)">Visualizar</button>
                                <button v-if="atualizar.visivel" class="dropdown-item" type="button" :data-toggle="atualizar.dataToggle" :data-target="atualizar.dataTarget" @click="setStore(obj.id)">Atualizar</button>
                                <button v-if="remover.visivel" class="dropdown-item" type="button" :data-toggle="remover.dataToggle" :data-target="remover.dataTarget" @click="setStore(obj.id)">Remover</button>
                            </div>
                        </div>
                    </td>
                </tr>                         

            </tbody>
        </table>
</template>

<script>
    export default {
        data(){
            return{
            }
        },
        props:['dados','titulos','visualizar','atualizar','remover', 'url'],
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
                //console.log(dadosFiltrados)
                return dadosFiltrados
            }
        },
        methods:{
            setStore(id){

                this.$store.commit('limparTransacao')
                this.$store.state.item = {}
                axios.get(`${this.url}/${id}`)
                    .then(response => {
                        this.$store.state.item = response.data
                        console.log(this.$store.state.item)
                        this.$emit('load-marca-options')
                        console.log('Emitido')
                    })
                .catch(errors=>{
                    console.log(errors)
                })


            }
        },
    }
</script>
