<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <!-- Inicio da Pesquisa -->
                <card-component titulo="Busca de Marcas">
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <div class="mb-3 col">
                                <input-component titulo="ID" id="inputId" id-help="idHelp" help-text="Informe o ID da marca.">
                                    <input type="number" id="inputId" class="form-control" placeholder="Ex.01">
                                </input-component>                            
                            </div>
                            <div class="mb-3 col">
                                <input-component titulo="Nome da Marca" id="inputNome" id-help="nomeHelp" help-text="Informe o nome da marca.">
                                    <input type="text" id="inputNome" class="form-control" placeholder="Ex.Toyota">
                                </input-component>                       
                            </div>
                        </div>
                    </template> 
                    <template v-slot:rodape>
                         <button type="submit" class="btn btn-primary float-right">Pesquisar</button>
                    </template> 
                </card-component>
                <!-- fim da Pesquisa-->


                <!-- Inicio da Listagem -->
                <card-component titulo="Listagem de Marcas">       
                    <template v-slot:conteudo>
                        <div class="form-row">
                            <table-component></table-component>  
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>
                <!-- fim da Listagem-->
                <modal-component id="modalMarca" title="Adicionar Marca">

                    <template v-slot:conteudo >
                        <div class="form-group">
                            <input-component titulo="Nome da Marca"  id="novoNome" id-help="novoNomeHelp" help-text="Informe o nome da marca.">
                                <input type="text" v-model="nomeMarca" id="novoNome" class="form-control" placeholder="Ex.Toyota">
                               
                            </input-component>
                        </div>
                        
                        <div class="form-group">
                            <input-component titulo="Imagem da Marca" id="novoImg" id-help="imgHelp" help-text="Selecione a imagem da marca.">
                                <input type="file" id="novoImg" class="form-control-file" @change="imgLoad">
                            </input-component> 
                            
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="salvar">Salvar</button>
                    </template>
                    
                </modal-component>
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
                urlBase: 'http://localhost:8000/api/v1/marca'
            }           
        },
        methods: {
            
            imgLoad(e){
                this.imgMarca = e.target.files
            },
            salvar(){
                console.log(this.imgMarca[0], this.nomeMarca)
                let formData = new FormData();
                formData.append('nome', this.nomeMarca)
                formData.append('imagem', this.imgMarca[0])

                axios.post(this.urlBase, formData)
                    .then(response =>{
                        console.log(response)
                    })
                    .catch(errors =>{
                        console.log(errors)
                    })
            }
        }
        
    }
</script>
