<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg">
                <!----------- Buscas ---------->
                <card-component titulo="Busca de Locações" :filter="true">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <input-component titulo="ID" id="inputId" id-help="idHelp" help-text="Informe o ID do Locacao.">
                                    <input type="number" id="inputId" class="form-control" placeholder="Ex.01" v-model="busca.id">
                                </input-component>                            
                            </div>
                            <div class="mb-3 col">
                                <input-component titulo="Numero da placa" id="inputNome" id-help="nomeHelp" help-text="Informe a placa do locacao.">
                                    <input type="text" id="inputNome" class="form-control" placeholder="Ex. ABC1D23" v-model="busca.placa">
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
                <!----------- Buscas ---------->

                <!----------- Listagem ---------->
                <card-component titulo="Listagem de Locações">       
                    <template v-slot:conteudo>
                        <div class="">
                            <table-component 
                                @load-modelo-options="load_Options"
                                :visualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalLocacaoVisualizar'}"
                                :remover="{visivel:true, dataToggle:'modal',dataTarget:'#modalLocacaoRemover'}"
                                :atualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalLocacaoAtualizar'}"
                                :finalizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalLocacaoFinalizar'}"
                                :url="urlBase"
                                :dados="locacoes.data"
                                :titulos="
                                    {
                                        id: {titulo: 'ID', tipo: 'text'},
                                        cliente: {titulo: 'Cliente',  tipo: 'fk', chave: 'nome'}, 
                                        carro_id: {titulo: 'Carro ID', tipo: 'text'}, 
                                        status: {titulo: 'Status', tipo: 'status'},
                                        data_inicio_periodo: {titulo: 'Início', tipo: 'date'},
                                        data_final_previsto_periodo: {titulo: 'Previsão', tipo: 'date'},
                                        valor_diaria: {titulo: 'Diária', tipo: 'money'},
                                        km_final: {titulo: 'Km Final', tipo: 'text'},
                                        data_final_realizado_periodo: {titulo: 'Finalizado', tipo: 'date'},
                                    }">
                            </table-component>  
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <div class="app-card-footer-actions w-100">
                            <paginate-component>
                                <li v-for="l,key in locacoes.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                    <a class="page-link" style="cursor: pointer;" v-html="l.label"></a>
                                </li>
                            </paginate-component>
                            <button type="button" @click="load_Options()" class="btn btn-primary" data-toggle="modal" data-target="#modalLocacao">+ Adicionar</button>
                        </div>
                    </template>
                </card-component>
                <!----------- Listagem ---------->

                <!---------- Modal Adicionar ------------>
                <modal-component id="modalLocacao" title="Adicionar Locacao">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar cadastrar." v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>

                    <template v-slot:conteudo>
                        
                        <div class="form-group">
                            <input-component titulo="Valor Diária"  id="novoPlaca" id-help="novoPlacaHelp" help-text="Informe placa do locacao no padrão mercosul.">
                                <input type="number" v-model="valor_diaria" id="novoPlaca" class="form-control" placeholder="Ex: R$: 500,00">
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component titulo="Cliente"  id="clienteId" id-help="clienteIdHelp" help-text="Informe o cliente da locacao.">
                                <select v-model="cliente_id" id="clienteId" class="form-control">  
                                    <option value="" disabled>Selecione o Cliente</option> 
                                    <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">{{cliente.nome}}</option>
                                </select>
                            </input-component>
                        </div>
                        <div class="form-group">
                            <input-component titulo="Carro"  id="carroId" id-help="carroIdHelp" help-text="Informe o carro da locacao.">
                                <select v-model="carro_id" id="carroId" class="form-control">  
                                    <option disabled value="" >Selecione o Carro</option> 
                                    <option v-for="carro in carros" :key="carro.id" :value="carro.id">{{carro.descricao}}</option>
                                </select>
                            </input-component>
                        </div>
                        
                        <div class="form-group">
                            <input-component titulo="Data de Inicio"  id="dataIni" id-help="dataIniHelp" help-text="Informe a data de inicio.">
                                <input type="date" v-model="data_inicio_periodo" id="dataIni" class="form-control">
                            </input-component>
                        </div>
                        <div class="form-group">
                            <input-component titulo="Data de previsao"  id="dataPrev" id-help="dataPrevHelp" help-text="Informe a data de previsao.">
                                <input type="date" v-model="data_final_previsto_periodo" id="dataPrev" class="form-control">
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
                <modal-component id="modalLocacaoVisualizar" title="Visualizar Locacao">
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
                            <input-component titulo="Status">
                                <input type="text" class="form-control" :value="$store.state.item.status" disabled> 
                            </input-component>
                            <input-component titulo="Cliente">
                                <input type="text" class="form-control" :value="$store.state.item.cliente.nome" disabled> 
                            </input-component>
                            <input-component titulo="Carro">
                                <input type="text" class="form-control" :value="$store.state.item.carro.descricao" disabled> 
                            </input-component>
                            <input-component titulo="Valor da Diária">
                                <input type="text" class="form-control" :value="$store.state.item.valor_diaria" disabled> 
                            </input-component>
                            <input-component titulo="Data de Inicio">
                                <input type="text" class="form-control" :value="$store.state.item.data_inicio_periodo | formataDataGlobal" disabled> 
                            </input-component>
                            <input-component titulo="Data de Previsao">
                                <input type="text" class="form-control" :value="$store.state.item.data_final_previsto_periodo | formataDataGlobal" disabled> 
                            </input-component>
                            <input-component titulo="Data de Finalização">
                                <input type="text" class="form-control" :value="$store.state.item.data_final_realizado_periodo | formataDataGlobal" disabled> 
                            </input-component>
                            <input-component titulo="Km Inicial">
                                <input type="text" class="form-control" :value="$store.state.item.km_inicial " disabled> 
                            </input-component>
                            <input-component titulo="Km Final">
                                <input type="text" class="form-control" :value="$store.state.item.km_final " disabled> 
                            </input-component>

                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </template>
                </modal-component>
                 <!---------- Modal Visualizar ---------->

                 <!---------- Mdodal Remover ------------>
                <modal-component id="modalLocacaoRemover" title="Remover Locacao">
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
                            <input-component titulo="Status">
                                <input type="text" class="form-control" :value="$store.state.item.status" disabled> 
                            </input-component>
                            <input-component titulo="Cliente">
                                <input type="text" class="form-control" :value="$store.state.item.cliente.nome" disabled> 
                            </input-component>
                            <input-component titulo="Carro">
                                <input type="text" class="form-control" :value="$store.state.item.carro.descricao" disabled> 
                            </input-component>
                            <input-component titulo="Valor da Diária">
                                <input type="text" class="form-control" :value="$store.state.item.valor_diaria" disabled> 
                            </input-component>
                            <input-component titulo="Data de Inicio">
                                <input type="text" class="form-control" :value="$store.state.item.data_inicio_periodo | formataDataGlobal" disabled> 
                            </input-component>
                            <input-component titulo="Data de Previsao">
                                <input type="text" class="form-control" :value="$store.state.item.data_final_previsto_periodo | formataDataGlobal" disabled> 
                            </input-component>
                            <input-component titulo="Data de Finalização">
                                <input type="text" class="form-control" :value="$store.state.item.data_final_realizado_periodo | formataDataGlobal" disabled> 
                            </input-component>
                            <input-component titulo="Km Inicial">
                                <input type="text" class="form-control" :value="$store.state.item.km_inicial " disabled> 
                            </input-component>
                            <input-component titulo="Km Final">
                                <input type="text" class="form-control" :value="$store.state.item.km_final " disabled> 
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
                <modal-component id="modalLocacaoAtualizar" title="Atualizar Locacao">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Atualização realizada com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar atualizar:" v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>
                   
                    <template v-slot:conteudo >
                        <div class="form-group">
                            <input-component titulo="Valor Diária"  id="novoPlaca" id-help="novoPlacaHelp" help-text="Informe placa do locacao no padrão mercosul.">
                                <input type="number" v-model="$store.state.item.valor_diaria" id="novoPlaca" class="form-control" placeholder="Ex: R$: 500,00">
                            </input-component>
                        </div>
                        <div class="form-group">
                            <input-component titulo="Cliente"  id="clienteId" id-help="clienteIdHelp" help-text="Informe o cliente da locacao.">
                                <select v-model="$store.state.item.cliente_id" id="clienteId" class="form-control">  
                                    <option value="" disabled>Selecione o Cliente</option> 
                                    <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">{{cliente.nome}}</option>
                                </select>
                            </input-component>
                        </div>
                        <div class="form-group">
                            <input-component titulo="Carro"  id="carroId" id-help="carroIdHelp" help-text="Informe o carro da locacao.">
                                <select v-model="$store.state.item.carro_id" id="carroId" class="form-control">  
                                    <option disabled value="" >Selecione o Carro</option> 
                                    <option v-for="carro in carros" :key="carro.id" :value="carro.id">{{carro.descricao}}</option>
                                </select>
                            </input-component>
                        </div>
                        
                        <div class="form-group">
                            <input-component titulo="Data de Inicio"  id="dataIni" id-help="dataIniHelp" help-text="Informe a data de inicio.">
                                <input type="date" v-model="$store.state.item.data_inicio_periodo" id="dataIni" class="form-control">
                            </input-component>
                        </div>
                        <div class="form-group">
                            <input-component titulo="Data de previsao"  id="dataPrev" id-help="dataPrevHelp" help-text="Informe a data de previsao.">
                                <input type="date" v-model="$store.state.item.data_final_previsto_periodo" id="dataPrev" class="form-control">
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
                cliente_id: '',
                carro_id:  '',
                data_inicio_periodo: '',
                data_final_previsto_periodo: '', 
                data_final_realizado_periodo: '', 
                valor_diaria: null, 
                km_final: null, 
                urlBase: 'http://localhost:8000/api/v1/locacao',
                urlPaginate: '',
                urlFiltro: '',
                transacaoStatus:'',
                transacaoDetalhes:{},
                locacoes: {data:[]},
                busca: {id: '', placa: ''},
                carros: false,
                clientes: false,
            }           
        },
        methods: {
            load_Options(){
                this.$store.commit('limparTransacao')
                if (this.clientes.length && this.carros.length) {
                    return
                }
                axios.get('/api/v1/cliente')
                .then(response =>{
                    this.clientes = response.data.data
                    //console.log(this.clientes)
                })
                .catch(errors =>{
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes ={
                        mensagem:errors.response.data.message,
                        dados: errors.response.data.errors
                    } 
                    //console.log(errors.response)
                })      

                axios.get('/api/v1/carro/disponiveis')
                .then(response =>{
                    this.carros_disp = response.data
                    //console.log(this.clientes)
                })
                .catch(errors =>{
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes ={
                        mensagem:errors.response.data.message,
                        dados: errors.response.data.errors
                    } 
                    //console.log(errors.response)
                })          
            },

            salvar(){
                
                let dados={
                    'cliente_id': this.cliente_id,
                    'carro_id': this.carro_id,
                    'data_inicio_periodo': this.data_inicio_periodo,
                    'data_final_previsto_periodo': this.data_final_previsto_periodo,
                    'valor_diaria': this.valor_diaria,
                }
                axios.post(this.urlBase, dados)
                    .then(response =>{                        
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'O locacao foi adicionado com sucesso!'
                        console.log(response.data)
                        
                    })
                    .catch(errors =>{
                        console.log('Erro ao cadastrar: ', errors.response)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                         
                        //console.log(errors.response)
                        //errors.response.data.message
                    })
                this.loadLocacoes() 
            },
            atualizar(){
                let url = this.urlBase + '/' + this.$store.state.item.id
                
                let dados = {
                    'modelo_id': this.$store.state.item.modelo_id,
                    'placa': this.$store.state.item.placa,
                    'disponivel': this.$store.state.item.disponivel,
                    'km': this.$store.state.item.km
                }

                axios.put(url, dados)
                    .then(response =>{
                        console.log(response.data)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'A locacao foi atualizada com sucesso!'                       
                    })
                    .catch(errors =>{
                        console.log('Erro ao Atualizar: ', errors.response)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                        //errors.response.data.message
                    })
                this.loadLocacoes()
            },
            remover(){
                let confirmacao = confirm('Tem certeza que deseja remover esse registro?')
                if (!confirmacao){
                    return false;
                }
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData();                
                axios.delete(url, formData)
                    .then(response =>{
                        console.log(' Removido com sucesso', response)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'O locacao foi atualizada com sucesso!'
                        this.loadLocacoes()
                        
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
                this.loadLocacoes() 
            },

            paginacao(l){
                if (l.url){
                    this.urlPaginate = l.url.split('?')[1]
                    this.loadLocacoes()
                }
            },
            
            loadLocacoes(){
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFiltro
                axios.get(url)
                .then(response =>{
                    this.locacoes = response.data
                    console.log(this.locacoes)
                })
                .catch(errors=>{
                    console.log(errors)
                })
                
            },

        },
        mounted(){
            this.loadLocacoes()
        }
        
    }
</script>
