<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg">
                
                <card-component titulo="Busca de carros">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <input-component titulo="ID" id="inputId" id-help="idHelp" help-text="Informe o ID do Carro.">
                                    <input type="number" id="inputId" class="form-control" placeholder="Ex.01" v-model="busca.id">
                                </input-component>                            
                            </div>
                            <div class="mb-3 col">
                                <input-component titulo="Nome do Carro" id="inputNome" id-help="nomeHelp" help-text="Informe o nome do carro.">
                                    <input type="text" id="inputNome" class="form-control" placeholder="Ex. Corolla" v-model="busca.nome">
                                </input-component>                       
                            </div>
                        </div>

                        <div class="row">
                            <div class="col d-flex justify-content-end">
                                <button @click="search" type="submit" class="btn btn-primary">Pesquisar</button>
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
                                        disponivel: {titulo: 'Disponivel', tipo: 'text'},
                                        km: {titulo: 'Quilometros', tipo: 'text'},
                                    }">
                            </table-component>  
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <paginate-component class="float-left">
                            <li v-for="l,key in carros.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                <a class="page-link" style="cursor: pointer;" v-html="l.label"></a>
                            </li>
                        </paginate-component>
                        <button type="button" @click="loadModeloOptions()" class="btn btn-primary float-right" data-toggle="modal" data-target="#modalModelo">Adicionar</button>
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
                <modal-component id="modalModeloVisualizar" title="Visualizar Carro">
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
                            <input-component titulo="Carro">
                                <input type="text" class="form-control" :value="$store.state.item.nome" disabled> 
                            </input-component>
                            <input-component titulo="Marca">
                                <input type="text" class="form-control" :value="$store.state.item.marca.nome" disabled> 
                            </input-component>
                            <input-component titulo="Foto do Carro:">
                                <img :src="'/storage/'+$store.state.item.imagem" :alt="$store.state.item.nome" width="400" v-if="$store.state.item.imagem">
                            </input-component>
                            <input-component titulo="Numero de Portas:">
                                <input type="text" class="form-control" :value="$store.state.item.numero_portas" disabled> 
                            </input-component>
                            <input-component titulo="Lugares:">
                                <input type="text" class="form-control" :value="$store.state.item.lugares" disabled> 
                            </input-component>
                            <input-component titulo="Air Bag:">
                                <input type="text" class="form-control" :value="$store.state.item.air_bag == 1 ? 'Sim' : 'Não'" disabled> 
                            </input-component>
                            <input-component titulo="Abs:">
                                <input type="text" class="form-control" :value="$store.state.item.abs == 1 ? 'Sim' : 'Não'"  disabled> 
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

                        <div>
                            <input-component titulo="ID">
                                <input type="text" class="form-control" :value="$store.state.item.id" disabled> 
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
                <modal-component id="modalModeloAtualizar" title="Atualizar Carro">
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
                        <div v-else>
                            <input-component titulo="Nome da Carro"  id="atualizarNome" >
                                <input type="text" v-model="$store.state.item.nome" id="atualizarNome" class="form-control" >
                            </input-component>
                            <input-component titulo="Marca do Carro"  id="marcaId" id-help="marcaIdHelp" help-text="Informe a marca do carro.">
                                    <select v-model="$store.state.item.marca_id" id="marcaId" class="form-control">  
                                        <option :value="null" disabled> Selecione a Marca </option>
                                        <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{marca.nome}}</option>
                                    </select>
                            </input-component>                            
                            <input-component titulo="Alterar Imagem do Carro" id="atualizarImg" >
                                <input type="file" id="atualizarImg" class="form-control-file" @change="imgLoad($event)">
                            </input-component> 
                            <input-component titulo="Numero de Portas:">
                                <input type="text" class="form-control" :value="$store.state.item.numero_portas" > 
                            </input-component>
                            <input-component titulo="Lugares:">
                                <input type="text" class="form-control" :value="$store.state.item.lugares" > 
                            </input-component>
                
                            <input-component titulo="Air Bag" id="airBag" id-help="airBagHelp" help-text="Informe se o carro possui air bag.">
                                <select v-model="air_bag" id="airBag" class="form-control">   
                                    <option :value="0">Nao</option>
                                    <option :value="1">Sim</option>
                                </select>
                            </input-component>
                            <input-component  titulo="ABS"  id="abs" id-help="absHelp" help-text="Informe se o carro possui freios ABS.">
                                <select v-model="abs" id="abs" class="form-control">   
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
                busca: {id: '', nome: ''},
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
                let formData = new FormData();
                formData.append('modelo_id', this.$store.state.item.modelo_id)
                formData.append('placa', this.$store.state.item.placa)
                formData.append('disponivel', this.$store.state.item.disponivel)
                formData.append('km', this.$store.state.item.km)

                axios.put(url, formData)
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
                    //console.log(this.carros)
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
