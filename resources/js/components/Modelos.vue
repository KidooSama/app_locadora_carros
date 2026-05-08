<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg">
                
                <card-component titulo="Busca de Modelos">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <input-component titulo="ID" id="inputId" id-help="idHelp" help-text="Informe o ID do Modelo.">
                                    <input type="number" id="inputId" class="form-control" placeholder="Ex.01" v-model="busca.id">
                                </input-component>                            
                            </div>
                            <div class="mb-3 col">
                                <input-component titulo="Nome do Modelo" id="inputNome" id-help="nomeHelp" help-text="Informe o nome do modelo.">
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
                <card-component titulo="Listagem de Modelos">       
                    <template v-slot:conteudo>
                        <div class="">
                            <table-component 
                                :visualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalModeloVisualizar'}"
                                :remover="{visivel:true, dataToggle:'modal',dataTarget:'#modalModeloRemover'}"
                                :atualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalModeloAtualizar'}"
                                :url="urlBase"
                                :dados="modelos.data"
                                :titulos="
                                    {
                                        id: {titulo: 'ID', tipo: 'text'},
                                        nome: {titulo: 'Nome', tipo: 'text'},
                                        carros_count: {titulo: 'Carros Registrados', tipo: 'text'},
                                        marca: {titulo: 'Marca', tipo: 'fk'},
                                    }"></table-component>  
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <paginate-component class="float-left">
                            <li v-for="l,key in modelos.links" :key="key" :class="l.active ? 'page-item active' : 'page-item'" @click="paginacao(l)">
                                <a class="page-link" style="cursor: pointer;" v-html="l.label"></a>
                            </li>
                        </paginate-component>
                        <button type="button" @click="loadMarcaOptions()" class="btn btn-primary float-right" data-toggle="modal" data-target="#modalModelo">Adicionar</button>
                    </template>
                </card-component>
                <!----------- Listagem ---------->

                <!---------- Modal Adicionar ------------>
                <modal-component id="modalModelo" title="Adicionar Modelo">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Cadastro realizado com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar excluir a modelo." v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>

                    <template v-slot:conteudo>
                        
                        <div class="form-group">
                            <input-component titulo="Nome do Modelo"  id="novoNome" id-help="novoNomeHelp" help-text="Informe o nome do modelo.">
                                <input type="text" v-model="nome" id="novoNome" class="form-control" placeholder="Ex: Corolla">
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component titulo="Marca do Modelo"  id="marcaId" id-help="marcaIdHelp" help-text="Informe a marca do modelo.">
                                <select v-model="marca_id" id="marcaId" class="form-control">  
                                    <option :value="false" disabled>Selecione o Modelo</option> 
                                    <option v-for="marca in marcas" :key="marca.id" :value="marca.id">{{marca.nome}}</option>
                                </select>
                            </input-component>
                        </div>
                        
                        <div class="form-group">
                            <input-component titulo="Número de Portas"  id="numeroPortas" id-help="numeroPortasHelp" help-text="Informe a quantidade de portas do veículo.">
                                <input type="number" v-model="numero_portas" id="numeroPortas" class="form-control" placeholder="Ex: 4">
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component titulo="Quantidade de Lugares"  id="lugares" id-help="lugaresHelp" help-text="Informe a quantidade de lugares do veículo.">
                                <input type="number" v-model="lugares" id="lugares" class="form-control" placeholder="Ex: 5">
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component titulo="Air Bag" id="airBag" id-help="airBagHelp" help-text="Informe se o modelo possui air bag.">
                                <select v-model="air_bag" id="airBag" class="form-control">   
                                    <option :value="0">Nao</option>
                                    <option :value="1">Sim</option>
                                </select>
                                
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component  titulo="ABS"  id="abs" id-help="absHelp" help-text="Informe se o modelo possui freios ABS.">
                                <select v-model="abs" id="abs" class="form-control">   
                                    <option :value="0">Nao</option>
                                    <option :value="1">Sim</option>
                                </select>
                            </input-component>
                        </div>

                        <div class="form-group">
                            <input-component titulo="Imagem do Modelo" id="novoImg" id-help="imgHelp" help-text="Selecione a imagem do modelo.">
                                <input type="file" id="novoImg" class="form-control-file" @change="imgLoad($event)">
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
                <modal-component id="modalModeloVisualizar" title="Visualizar Modelo">
                    <template v-slot:conteudo>
                        <input-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled> 
                        </input-component>
                        <input-component titulo="Modelo">
                            <input type="text" class="form-control" :value="$store.state.item.nome" disabled> 
                        </input-component>
                        <input-component titulo="Foto do Modelo:">
                            <img :src="'/storage/'+$store.state.item.imagem" alt="" v-if="$store.state.item.imagem">
                        </input-component>
                        <input-component titulo="Numero de Portas:">
                            <input type="text" class="form-control" :value="$store.state.item.numero_portas" disabled> 
                        </input-component>
                        <input-component titulo="Lugares:">
                            <input type="text" class="form-control" :value="$store.state.item.lugares" disabled> 
                        </input-component>
                        <input-component titulo="Air Bag:">
                     
                            <input type="text" class="form-control" :value="$store.state.item.air_bag" disabled> 
                        </input-component>
                        <input-component titulo="Abs:">
                            <input type="text" class="form-control" :value="$store.state.item.abs" disabled> 
                        </input-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </template>
                </modal-component>
                 <!---------- Modal Visualizar ---------->

                 <!---------- Mdodal Remover ------------>
                <modal-component id="modalModeloRemover" title="Remover Modelo">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Exclusão realizada com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar excluir a modelo." v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>
                    <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                        <input-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled> 
                        </input-component>
                        <input-component titulo="Modelo">
                            <input type="text" class="form-control" :value="$store.state.item.nome" disabled> 
                        </input-component>
                        <input-component titulo="Logo: ">
                            <img :src="'/storage/'+$store.state.item.imagem" alt="" v-if="$store.state.item.imagem">
                        </input-component>
                        <input-component titulo="Data de criação">
                            <input type="text" class="form-control" :value="$store.state.item.created_at | formataDataTempoGlobal" disabled> 
                        </input-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
                    </template>
                </modal-component>
                 <!----------- Modal Remover ------------>

                 <!---------- Mdodal Atualizar ---------->
                <modal-component id="modalModeloAtualizar" title="Atualizar Modelo">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Atualização realizada com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar atualizar a modelo:" v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>
                   
                    <template v-slot:conteudo >
                        
                        <input-component titulo="Nome da Modelo"  id="atualizarNome" >
                            <input type="text" v-model="$store.state.item.nome" id="atualizarNome" class="form-control" >
                        </input-component>
                        <input-component titulo="Marca do Modelo"  id="marcaId" id-help="marcaIdHelp" help-text="Informe a marca do modelo.">
                                <select v-model="$store.state.item.marca_id" id="marcaId" class="form-control">  
                                    <option :value="false" disabled>Selecione o Modelo</option> 
                                    <option v-for="marca in $store.state.item.marca" :key="marca.id" :value="marca.id">{{marca.nome}}</option>
                                </select>
                        </input-component>
                        <input-component titulo="Foto do Modelo:">
                            <img :src="'/storage/'+$store.state.item.imagem" alt="" v-if="$store.state.item.imagem">
                        </input-component>
                        <input-component titulo="Alterar Imagem do Modelo" id="atualizarImg" >
                            <input type="file" id="atualizarImg" class="form-control-file" @change="imgLoad($event)">
                        </input-component> 
                        <input-component titulo="Numero de Portas:">
                            <input type="text" class="form-control" :value="$store.state.item.numero_portas" > 
                        </input-component>
                        <input-component titulo="Lugares:">
                            <input type="text" class="form-control" :value="$store.state.item.lugares" > 
                        </input-component>
            
                        <input-component titulo="Air Bag" id="airBag" id-help="airBagHelp" help-text="Informe se o modelo possui air bag.">
                            <select v-model="air_bag" id="airBag" class="form-control">   
                                <option :value="0">Nao</option>
                                <option :value="1">Sim</option>
                            </select>
                        </input-component>
                        <input-component  titulo="ABS"  id="abs" id-help="absHelp" help-text="Informe se o modelo possui freios ABS.">
                            <select v-model="abs" id="abs" class="form-control">   
                                <option :value="0">Nao</option>
                                <option :value="1">Sim</option>
                            </select>
                        </input-component>

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
                imagem: [],
                marca_id: false,
                imagem: '', 
                numero_portas: '',
                lugares: '', 
                air_bag: 1, 
                abs: 0, 
                urlBase: 'http://localhost:8000/api/v1/modelo',
                urlPaginate: '',
                urlFiltro: '',
                transacaoStatus:'',
                transacaoDetalhes:{},
                modelos: {data:[]},
                busca: {id: '', nome: ''},
                marcas: [],
            }           
        },
        methods: {
            loadMarcaOptions(){
                this.$store.commit('limparTransacao')
                if (this.marcas.length) {
                    return
                }
                axios.get('/api/v1/marca')
                .then(response =>{
                    this.marcas = response.data.data
                    console.log(this.marcas)
                })
                .catch(errors =>{
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes ={
                        mensagem:errors.response.data.message,
                        dados: errors.response.data.errors
                    } 
                    console.log(errors.response)
                })              
            },
            salvar(){
                
                let formData = new FormData();
                formData.append('marca_id', this.marca_id)
                formData.append('nome', this.nome)
                formData.append('imagem', this.imagem[0])
                formData.append('numero_portas', this.numero_portas)
                formData.append('lugares', this.lugares)
                formData.append('air_bag', this.air_bag)
                formData.append('abs', this.abs)

                axios.post(this.urlBase, formData)
                    .then(response =>{                        
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'O modelo foi adicionado com sucesso!'
                        console.log(response.data)
                        
                    })
                    .catch(errors =>{
                        console.log('Erro ao Atualizar: ', errors.response)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                         
                        console.log(errors.response)
                        //errors.response.data.message
                    })
                this.loadModelos() 
            },
            atualizar(){
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData();
                formData.append('nome', this.$store.state.item.nome)
                console.log(this.$store.state.item.nome)
                formData.append('_method', 'put')

                if (this.imagem[0]) {
                    formData.append('imagem', this.imagem[0])
                    console.log(this.$store.state.item.nome)
                }

                axios.post(url, formData)
                    .then(response =>{
                        console.log(response.data)
                        atualizarImg.value = ''
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'A modelo foi atualizada com sucesso!'
                        
                        
                    })
                    .catch(errors =>{
                        console.log('Erro ao Atualizar: ', errors.response)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                        //errors.response.data.message
                    })
                this.loadModelos()
            },
            remover(){
                let confirmacao = confirm('Tem certeza que deseja remover esse registro?')
                if (!confirmacao){
                    return false;
                }
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData();
                formData.append('_method', 'delete')
                
                axios.post(url, formData)
                    .then(response =>{
                        console.log(' Removido com sucesso', response)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'O modelo foi atualizada com sucesso!'
                        this.loadModelos()
                        
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
                this.loadModelos() 
            },

            paginacao(l){
                if (l.url){
                    this.urlPaginate = l.url.split('?')[1]
                    this.loadModelos()
                }
            },
            
            loadModelos(){
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFiltro
                axios.get(url)
                .then(response =>{
                    this.modelos = response.data
                    console.log(this.modelos)
                })
                .catch(errors=>{
                    console.log(errors)
                })
                
            },
            
            imgLoad(e){
                this.imagem = e.target.files
            },

        },
        mounted(){
            this.loadModelos()
        }
        
    }
</script>
