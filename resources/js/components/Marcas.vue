<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <!-- Inicio da Pesquisa -->
                <div class="card mb-3">
                    <div class="card-header">Busca de Marcas</div>
                    <div class="card-body form-row">

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
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right">Pesquisar</button>
                    </div>
                </div>
                <!-- fim da Pesquisa-->

                <!-- Inicio da Listagem -->
                <div class="card">
                    <div class="card-header">Listagem de Marcas</div>
                    <div class="card-body form-row">

                        <table-component></table-component>                        
                       
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary float-right">Adicionar</button>
                    </div>
                </div>
                <!-- fim da Listagem-->
                
            </div>
        </div>
    </div>
</template>

<script>

</script>
