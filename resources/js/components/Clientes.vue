<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg">
                
                <card-component titulo="Busca de Clientes" :filter="true">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <input-component titulo="ID" id="inputId" id-help="idHelp" help-text="Informe o ID do Cliente.">
                                    <input type="number" id="inputId" class="form-control" placeholder="Ex.01" v-model="busca.id">
                                </input-component>                            
                            </div>
                            <div class="mb-3 col">
                                <input-component titulo="Nome do Cliente" id="inputNome" id-help="nomeHelp" help-text="Informe a placa do cliente.">
                                    <input type="text" id="inputNome" class="form-control" placeholder="Ex. Corolla" v-model="busca.nome">
                                </input-component>                       
                            </div>
                        </div>

                        <div class="row">
                            <div class="col d-flex justify-content-end">
                                <button @click="search" type="submit" class="btn btn-primary px-4">Pesquisar</button>
                            </div>
                        </div>
                    </template> 
                </card-component>

                <!----------- Listagem ---------->
                <card-component titulo="Listagem de Clientes">       
                    <template v-slot:conteudo>
                        <div class="">
                            <table-component 
                                
                                :visualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalClienteVisualizar'}"
                                :remover="{visivel:true, dataToggle:'modal',dataTarget:'#modalClienteRemover'}"
                                :atualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalClienteAtualizar'}"
                                :url="urlBase"
                                :dados="clientes"
                                :titulos="
                                    {
                                        id: {titulo: 'ID', tipo: 'text'},
                                        nome: {titulo: 'Cliente', tipo: 'text'}, 
                                        created_at:{titulo: 'Criado', tipo: 'data'}
                                     
                                    }">
                            </table-component>  
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <div class="app-card-footer-actions w-100">
                            <paginate-component>
                                <li v-for="l,key in clientes.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                    <a class="page-link" style="cursor: pointer;" v-html="l.label"></a>
                                </li>
                            </paginate-component>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalModelo">+ Adicionar</button>
                        </div>
                    </template>
                </card-component>
                <!----------- Listagem ---------->

                <!---------- Modal Adicionar ------------>
                <modal-component id="modalModelo" title="Adicionar Cliente">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar cadastrar." v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>

                    <template v-slot:conteudo>
                        
                        <div class="form-group">
                            <input-component titulo="Nome do Cliente"  id="novoNome" id-help="novoNomeHelp" help-text="Informe nome do cliente no padrão mercosul.">
                                <input type="text" v-model="nome" id="novoNome" class="form-control" placeholder="Ex: ABC1D23">
                            </input-component>
                        </div>

                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
                    </template>
                </modal-component>
                <!---------- Modal Adicionar ------------>


                <!--------- Modal Visualizar ----------->
                <modal-component id="modalClienteVisualizar" title="Visualizar Cliente">
                    <template v-slot:conteudo>
                        <div class="d-flex justify-content-center" v-if="!$store.state.item.id">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div v-else>
                            <input-component titulo="ID">
                                <input type="text" class="form-control" :value="$store.state.item.id" disabled> 
                            </input-component>
                            <input-component titulo="Cliente">
                                <input type="text" class="form-control" :value="$store.state.item.nome" disabled> 
                            </input-component>
                            <input-component titulo="Data de criação">
                                <input type="text" class="form-control" :value="$store.state.item.created_at | formataDataTempoGlobal" disabled> 
                            </input-component>
                            
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </template>
                </modal-component>
                 <!---------- Modal Visualizar ---------->

                 <!---------- Mdodal Remover ------------>
                <modal-component id="modalClienteRemover" title="Remover Cliente">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Exclusão realizada com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar excluir." v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>
                    <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                        <div class="d-flex justify-content-center" v-if="!$store.state.item.id">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div v-else>
                            <input-component titulo="ID">
                                <input type="text" class="form-control" :value="$store.state.item.id" disabled> 
                            </input-component>
                            <input-component titulo="Cliente">
                                <input type="text" class="form-control" :value="$store.state.item.nome" disabled> 
                            </input-component>
                            <input-component titulo="Data de criação">
                                <input type="text" class="form-control" :value="$store.state.item.created_at | formataDataTempoGlobal" disabled> 
                            </input-component>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
                    </template>
                </modal-component>
                 <!----------- Modal Remover ------------>

                 <!---------- Mdodal Atualizar ---------->
                <modal-component id="modalClienteAtualizar" title="Atualizar Cliente">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Atualização realizada com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar atualizar:" v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>
                   
                    <template v-slot:conteudo >
                        <div class="d-flex justify-content-center" v-if="!$store.state.item.id">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="form-group" v-else>
                            <input-component titulo="Nome do Cliente"  id="novoNome" id-help="novoNomeHelp" help-text="Informe nome do cliente atualizado.">
                                <input type="text" v-model="$store.state.item.nome" id="novoNome" class="form-control" placeholder="Ex: ABC1D23">
                            </input-component>
                        </div>

                    </template>

                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="atualizar()">Salvar</button>
                    </template>
                </modal-component>
                 <!---------- Mdodal Atualizar ---------->

            </div>
        </div>
    </div>
</template>

<script>
import { error } from 'jquery';


    export default{
        data(){
            return{
                nome: '',
                urlBase: 'http://localhost:8000/api/v1/cliente',
                urlPaginate: '',
                urlFiltro: '',
                transacaoStatus:'',
                transacaoDetalhes:{},
                busca: {id: '', nome: ''},
                clientes: []

            }           
        },
        methods: {
            // loadModeloOptions(){
            //     this.$store.commit('limparTransacao')
            //     if (this.modelos.length) {
            //         return
            //     }
            //     axios.get('/api/v1/modelo')
            //     .then(response =>{
            //         this.modelos = response.data.data
            //         //console.log(this.modelos)
            //     })
            //     .catch(errors =>{
            //         this.transacaoStatus = 'erro'
            //         this.transacaoDetalhes ={
            //             mensagem:errors.response.data.message,
            //             dados: errors.response.data.errors
            //         } 
            //         //console.log(errors.response)
            //     })              
            // },
            salvar(){
                
                let dados = {
                    'nome' : this.nome
                }
                axios.post(this.urlBase, dados)
                    .then(response =>{                        
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'O cliente foi adicionado com sucesso!'
                        
                        
                    })
                    .catch(errors =>{
                        console.log('Erro ao cadastrar: ', errors.response)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors

                    })
                this.loadClientes() 
            },
            atualizar(){
                let url = this.urlBase + '/' + this.$store.state.item.id
                
                let dados = {
                    'nome' : this.$store.state.item.nome,
                }

                axios.put(url, dados)
                    .then(response =>{
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'O cliente foi atualizado com sucesso!'                       
                    })
                    .catch(errors =>{
                        console.log('Erro ao Atualizar: ', errors.response)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                        //errors.response.data.message
                    })
                this.loadClientes()
            },
            remover(){
                let confirmacao = confirm('Tem certeza que deseja remover esse registro?')
                if (!confirmacao){
                    return false;
                }
                let url = this.urlBase + '/' + this.$store.state.item.id
                            
                axios.delete(url)
                    .then(response =>{
                        console.log(' Removido com sucesso', response)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'O cliente foi atualizada com sucesso!'
                        this.loadClientes()
                        
                    })
                    .catch(errors =>{
                        console.log('erro', errors.data)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                    
                    })
            },
            search(){
                let filtro = ''
                for (let chave in this.busca){

                    if(this.busca[chave]){
                        if (filtro != ''){
                            filtro += ";"
                        }
                        filtro += chave + ':like:' + '%' +this.busca[chave]+'%'  
                    }                    
                }
                if (filtro != '') {
                    this.urlPaginate = 'page=1'
                    this.urlFiltro = '&filtro='+filtro
                    
                }else{
                    this.urlFiltro = ''
                }
                this.loadClientes() 
            },

            paginacao(l){
                if (l.url){
                    this.urlPaginate = l.url.split('?')[1]
                    this.loadClientes()
                }
            },
            
            loadClientes(){
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFiltro
                axios.get(url)
                .then(response =>{
                    this.clientes = response.data.data
                    console.log(this.clientes)
                })
                .catch(errors=>{
                    console.log(errors)
                })
                
            },

        },
        mounted(){
            this.loadClientes()
        }
        
    }
</script>
