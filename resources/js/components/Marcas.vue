<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg">
                
                <!-------- Inicio da Pesquisa --------->
                <card-component titulo="Busca de Marcas">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="mb-3 col">
                                <input-component titulo="ID" id="inputId" id-help="idHelp" help-text="Informe o ID da marca.">
                                    <input type="number" id="inputId" class="form-control" placeholder="Ex.01" v-model="busca.id">
                                </input-component>                            
                            </div>
                            <div class="mb-3 col">
                                <input-component titulo="Nome da Marca" id="inputNome" id-help="nomeHelp" help-text="Informe o nome da marca.">
                                    <input type="text" id="inputNome" class="form-control" placeholder="Ex.Toyota" v-model="busca.nome">
                                </input-component>                       
                            </div>
                            <button @click="search" type="submit" class="btn btn-primary float-right">Pesquisar</button>
                        </div>
                    </template> 
                </card-component>
                <!-------- Inicio da Pesquisa --------->

                <!----------- Listagem ---------->
                <card-component titulo="Listagem de Marcas">       
                    <template v-slot:conteudo>
                        <div class="">
                            <table-component 
                                :visualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalMarcaVisualizar'}"
                                :remover="{visivel:true, dataToggle:'modal',dataTarget:'#modalMarcaRemover'}"
                                :atualizar="{visivel:true, dataToggle:'modal',dataTarget:'#modalMarcaAtualizar'}"
                                :titulos="
                                    {
                                        id: {titulo: 'ID', tipo: 'text'},
                                        nome: {titulo: 'Nome', tipo: 'text'},
                                        imagem: {titulo: 'Imagem', tipo: 'img'},
                                        created_at: {titulo: 'Criação', tipo: 'data'}
                                    }"

                                :marcas="marcas.data"></table-component>  
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <paginate-component class="float-left">
                            <li v-for="l,key in marcas.links" :key="key" 
                                :class="l.active ? 'page-item active' : 'page-item'" 
                                @click="paginacao(l)"
                            >
                                <a class="page-link" style="cursor: pointer;" v-html="l.label"></a>
                            </li>
                        </paginate-component>
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
                <!----------- Listagem ---------->

                <!---------- Modal Adicionar ------------>
                <modal-component id="modalMarca" title="Adicionar Marca">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Marca cadastrada com sucesso!" v-if="transacaoStatus == 'adicionado'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar a marca." v-if="transacaoStatus == 'erro'" ></alert-component>
                    </template>
                    <template v-slot:conteudo >
                        <div class="form-group">
                            <input-component titulo="Nome da Marca"  id="novoNome" id-help="novoNomeHelp" help-text="Informe o nome da marca.">
                                <input type="text" v-model="nomeMarca" id="novoNome" class="form-control" placeholder="Ex.Toyota">
                            </input-component>
                        </div>
                        
                        <div class="form-group">
                            <input-component titulo="Imagem da Marca" id="novoImg" id-help="imgHelp" help-text="Selecione a imagem da marca.">
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
                <modal-component id="modalMarcaVisualizar" title="Visualizar Marca">
                    <template v-slot:conteudo>
                        <input-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled> 
                        </input-component>
                        <input-component titulo="Marca">
                            <input type="text" class="form-control" :value="$store.state.item.nome" disabled> 
                        </input-component>
                        <input-component titulo="Logo: ">
                            <img :src="'storage/'+$store.state.item.imagem" alt="" v-if="$store.state.item.imagem">
                        </input-component>
                        <input-component titulo="Data de criação">
                            <input type="text" class="form-control" :value="$store.state.item.created_at | formataDataTempoGlobal" disabled> 
                        </input-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </template>
                </modal-component>
                 <!---------- Modal Visualizar ---------->

                 <!---------- Mdodal Remover ------------>
                <modal-component id="modalMarcaRemover" title="Remover Marca">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Exclusão realizada com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar excluir a marca." v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>
                    <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
                        <input-component titulo="ID">
                            <input type="text" class="form-control" :value="$store.state.item.id" disabled> 
                        </input-component>
                        <input-component titulo="Marca">
                            <input type="text" class="form-control" :value="$store.state.item.nome" disabled> 
                        </input-component>
                        <input-component titulo="Logo: ">
                            <img :src="'storage/'+$store.state.item.imagem" alt="" v-if="$store.state.item.imagem">
                        </input-component>
                        <input-component titulo="Data de criação">
                            <input type="text" class="form-control" :value="$store.state.item.created_at" disabled> 
                        </input-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
                    </template>
                </modal-component>
                 <!----------- Modal Remover ------------>

                 <!---------- Mdodal Atualizar ---------->
                <modal-component id="modalMarcaAtualizar" title="Atualizar Marca">
                    <template v-slot:alertas>
                        <alert-component tipo="success" :detalhes="$store.state.transacao" titulo="Atualização realizada com sucesso!" v-if="$store.state.transacao.status == 'sucesso'" ></alert-component>
                        <alert-component tipo="danger" :detalhes="$store.state.transacao" titulo="Erro ao tentar atualizar a marca:" v-if="$store.state.transacao.status == 'erro'"></alert-component>
                    </template>
                   
                    <template v-slot:conteudo >
                        <div class="form-group">
                            
                            <input-component titulo="Nome da Marca"  id="atualizarNome" id-help="atualizarNomeHelp" help-text="Informe o nome da marca.">
                                <input type="text" v-model="$store.state.item.nome" id="atualizarNome" class="form-control" >
                            </input-component>
                        </div>
                        
                        <div class="form-group">
                            <input-component titulo="Imagem da Marca" id="atualizarImg" id-help="atualizarImgHelp" help-text="Selecione a imagem da marca.">
                                <input type="file" id="atualizarImg" class="form-control-file" @change="imgLoad($event)">
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

    export default{
        data(){
            return{
                nomeMarca: '',
                imgMarca: [],
                urlBase: 'http://localhost:8000/api/v1/marca',
                urlPaginate: '',
                urlFiltro: '',
                transacaoStatus:'',
                transacaoDetalhes:{},
                marcas: {data:[]},
                busca: {id: '', nome: ''}
            }           
        },
        methods: {
            atualizar(){
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData();
                formData.append('nome', this.$store.state.item.nome)
                formData.append('_method', 'put')

                if (this.imgMarca[0]) {
                    formData.append('imagem', this.imgMarca[0])
                }

                axios.post(url, formData)
                    .then(response =>{
                        console.log(response.data)
                        atualizarImg.value = ''
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'A marca foi atualizada com sucesso!'
                        
                        
                    })
                    .catch(errors =>{
                        console.log('Erro ao Atualizar: ', errors.response)
                        this.$store.state.transacao.status = 'erro'
                        this.$store.state.transacao.mensagem = errors.response.data.message
                        this.$store.state.transacao.dados = errors.response.data.errors
                        //errors.response.data.message
                    })
                this.loadMarcas()
            },
            remover(){
                let confirmacao = confirm('Tem certeza que deseja remover esse registro?')
                if (!confirmacao){
                    return false;
                }
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData();
                formData.append('_method', 'delete')
                console.log(url)
                axios.post(url, formData)
                    .then(response =>{
                        console.log(' Removido com sucesso', response)
                        this.$store.state.transacao.status = 'sucesso'
                        this.$store.state.transacao.mensagem = 'A marca foi atualizada com sucesso!'
                        this.loadMarcas()
                        
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
                this.loadMarcas()
                
                
            },
            paginacao(l){
                if (l.url){
                this.urlPaginate = l.url.split('?')[1]
                this.loadMarcas()
            }
            },
            loadMarcas(){
                let url = this.urlBase + '?' + this.urlPaginate + this.urlFiltro
                axios.get(url)
                .then(response =>{
                    this.marcas = response.data
                })
                .catch(errors=>{
                    console.log(errors)
                })
            },
            
            imgLoad(e){
                this.imgMarca = e.target.files
            },
            salvar(){
                
                let formData = new FormData();
                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.imgMarca[0])

                axios.post(this.urlBase, formData)
                    .then(response =>{
                        this.transacaoStatus = 'adicionado'
                        this.transacaoDetalhes = {
                            mensagem: 'ID do registro: '+response.data.id 
                        }
                        console.log(response.data)
                        
                    })
                    .catch(errors =>{
                        this.transacaoStatus = 'erro'
                        this.transacaoDetalhes ={
                            mensagem:errors.response.data.message,
                            dados: errors.response.data.errors
                        } 
                        console.log(errors.response)
                        //errors.response.data.message
                    })
            }
        },
        mounted(){
            this.loadMarcas()
        }
        
    }
</script>
