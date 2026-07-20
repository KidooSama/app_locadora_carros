<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg">
                
                <card-component titulo="Busca de Carros" :filter="true">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <input-component titulo="ID" id="inputId" id-help="idHelp" help-text="Informe o ID do Carro.">
                                    <input type="number" id="inputId" class="form-control" placeholder="Ex.01" v-model="busca.id">
                                </input-component>                            
                            </div>
                            <div class="mb-3 col">
                                <input-component titulo="Numero da placa" id="inputNome" id-help="nomeHelp" help-text="Informe a placa do carro.">
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

                <!----------- Listagem ---------->
                <card-component titulo="Listagem de Carros">       
                    <template v-slot:conteudo>
                        <div class="">
                            <table-component 
                                @load-modelo-options="loadModeloOptions"
                                :visualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalCarroVisualizar'}"
                                :remover="{visivel:true, dataToggle:'modal',dataTarget:'#modalCarroRemover'}"
                                :atualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalCarroAtualizar'}"
                                :url="urlBase"
                                :dados="carros.data"
                                :titulos="
                                    {
                                        id: {titulo: 'ID', tipo: 'text'},
                                        placa: {titulo: 'Placa', tipo: 'text'}, 
                                        modelo: {titulo: 'Modelo', tipo: 'fk', chave: 'nome'}, 
                                        disponivel: {titulo: 'Disponível', tipo: 'bool'},
                                        km: {titulo: 'Quilometros', tipo: 'km'},
                                    }">
                            </table-component>  
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <div class="app-card-footer-actions w-100">
                            <paginate-component>
                                <li v-for="l,key in carros.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                    <a class="page-link" style="cursor: pointer;" v-html="l.label"></a>
                                </li>
                            </paginate-component>
                            <button type="button" @click="loadModeloOptions()" class="btn btn-primary" data-toggle="modal" data-target="#modalModelo">+ Adicionar</button>
                        </div>
                    </template>
                </card-component>
                <!----------- Listagem ---------->

                <!---------- Modal Adicionar ------------>
                <modal-component id="modalModelo" title="Adicionar Carro">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar cadastrar." v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>

                    <template v-slot:conteudo>
                        
                        <div class="form-group">
                            <input-component titulo="Placa do Carro"  id="novoPlaca" id-help="novoPlacaHelp" help-text="Informe placa do carro no padrão mercosul.">
                                <input type="text" v-model="placa" id="novoPlaca" class="form-control" placeholder="Ex: ABC1D23">
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component titulo="Modelo do Carro"  id="modeloId" id-help="modeloIdHelp" help-text="Informe o modelo do carro.">
                                <select v-model="modelo_id" id="modeloId" class="form-control">  
                                    <option :value="false" disabled>Selecione o Modelo</option> 
                                    <option v-for="modelo in modelos" :key="modelo.id" :value="modelo.id">{{modelo.nome}}</option>
                                </select>
                            </input-component>
                        </div>
                        
                        <div class="form-group">
                            <input-component titulo="Qual a quilometragem do carro?"  id="km" id-help="kmHelp" help-text="Informe quantos km rodados o carro possui.">
                                <input type="number" v-model="km" id="km" class="form-control" placeholder="Ex: 40.000">
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component titulo="O carro se encontra disponivel?" id="disponivel" id-help="disponivelHelp" help-text="Informe o carro está disponivel.">
                                <select v-model="disponivel" id="disponivel" class="form-control">   
                                    <option :value="0">Nao</option>
                                    <option :value="1">Sim</option>
                                </select>                                
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
                <modal-component id="modalCarroVisualizar" title="Visualizar Carro">
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
                            <input-component titulo="Modelo">
                                <input type="text" class="form-control" :value="$store.state.item.modelo.nome" disabled> 
                            </input-component>
                            <input-component titulo="Imagem do Modelo">
                                <img :src="$imgUrl($store.state.item.modelo.imagem)" class="app-modal-img" :alt="$store.state.item.modelo.nome" v-if="$store.state.item.modelo && $store.state.item.modelo.imagem">
                            </input-component>
                            <input-component titulo="Placa">
                                <input type="text" class="form-control" :value="$store.state.item.placa" disabled> 
                            </input-component>
                            <input-component titulo="Disponivel">
                                <input type="text" class="form-control" :value="$store.state.item.disponivel == 1 ? 'Sim' : 'Não'" disabled> 
                            </input-component>
                            <input-component titulo="Quilometragem">
                                <input type="text" class="form-control" :value="$store.state.item.km" disabled> 
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
                <modal-component id="modalCarroRemover" title="Remover Carro">
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
                            <input-component titulo="Modelo">
                                <input type="text" class="form-control" :value="$store.state.item.modelo.nome" disabled> 
                            </input-component>
                            <input-component titulo="Placa">
                                <input type="text" class="form-control" :value="$store.state.item.placa" disabled> 
                            </input-component>
                            <input-component titulo="Disponivel">
                                <input type="text" class="form-control" :value="$store.state.item.disponivel == 1 ? 'Sim' : 'Não'" disabled> 
                            </input-component>
                            <input-component titulo="Quilometragem">
                                <input type="text" class="form-control" :value="$store.state.item.km" disabled> 
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
                <modal-component id="modalCarroAtualizar" title="Atualizar Carro">
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
                            <input-component titulo="Placa do Carro"  id="novoPlaca" id-help="novoPlacaHelp" help-text="Informe placa do carro no padrão mercosul.">
                                <input type="text" v-model="$store.state.item.placa" id="novoPlaca" class="form-control" placeholder="Ex: ABC1D23">
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component titulo="Modelo do Carro"  id="modeloId" id-help="modeloIdHelp" help-text="Informe o modelo do carro.">
                                <select v-model="$store.state.item.modelo_id" id="modeloId" class="form-control">  
                                    <option :value="false" disabled>Selecione o Modelo</option> 
                                    <option v-for="modelo in modelos" :key="modelo.id" :value="modelo.id">{{modelo.nome}}</option>
                                </select>
                            </input-component>
                        </div>
                        
                        <div class="form-group">
                            <input-component titulo="Qual a quilometragem do carro?"  id="km" id-help="kmHelp" help-text="Informe quantos km rodados o carro possui.">
                                <input type="number" v-model="$store.state.item.km" id="km" class="form-control" placeholder="Ex: 40.000">
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component titulo="O carro se encontra disponivel?" id="disponivel" id-help="disponivelHelp" help-text="Informe o carro está disponivel.">
                                <select v-model="$store.state.item.disponivel" id="disponivel" class="form-control">   
                                    <option :value="0">Nao</option>
                                    <option :value="1">Sim</option>
                                </select>                                
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
                marca_id: false,
                modelo_id: false,
                placa: '',
                disponivel: 1, 
                km: '', 
                urlBase: 'http://localhost:8000/api/v1/carro',
                urlPaginate: '',
                urlFiltro: '',
                transacaoStatus:'',
                transacaoDetalhes:{},
                carros: {data:[]},
                busca: {id: '', placa: ''},
                marcas: [],
                modelos: [],
            }           
        },
        methods: {

            loadModeloOptions(){
                this.$store.commit('limparTransacao')
                if (this.modelos.length) {
                    return
                }
                axios.get('/api/v1/modelo')
                .then(response =>{
                    this.modelos = response.data.data
                    //console.log(this.modelos)
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
                
                let formData = new FormData();
                formData.append('modelo_id', this.modelo_id)
                formData.append('placa', this.placa)
                formData.append('disponivel', this.disponivel)
                formData.append('km', this.km)
                axios.post(this.urlBase, formData)
                    .then(response =>{                        
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'O carro foi adicionado com sucesso!'
                        //console.log(response.data)
                        
                    })
                    .catch(errors =>{
                        console.log('Erro ao cadastrar: ', errors.response)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                         
                        //console.log(errors.response)
                        //errors.response.data.message
                    })
                this.loadCarros() 
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
                        this.$store.state.transacao.mensagem = 'A carro foi atualizada com sucesso!'                       
                    })
                    .catch(errors =>{
                        console.log('Erro ao Atualizar: ', errors.response)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                        //errors.response.data.message
                    })
                this.loadCarros()
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
                        this.$store.state.transacao.mensagem = 'O carro foi atualizada com sucesso!'
                        this.loadCarros()
                        
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
                this.loadCarros() 
            },

            paginacao(l){
                if (l.url){
                    this.urlPaginate = l.url.split('?')[1]
                    this.loadCarros()
                }
            },
            
            loadCarros(){
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFiltro
                axios.get(url)
                .then(response =>{
                    this.carros = response.data
                    console.log(this.carros)
                })
                .catch(errors=>{
                    console.log(errors)
                })
                
            },

        },
        mounted(){
            this.loadCarros()
        }
        
    }
</script>
