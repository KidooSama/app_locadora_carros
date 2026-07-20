<template>
    <div class="app-dashboard-page">
        <div class="app-dashboard-wrapper">
            <div class="page-header-custom d-flex align-items-end">
                <div>
                    <h1 class="page-title mb-1">Painel</h1>
                    <p class="page-subtitle mb-0">Visão geral da locadora em um só lugar.</p>
                </div>
                <div class="ml-auto d-none d-sm-block">
                    <span class="text-muted" style="font-size: 0.85rem;">
                        <i class="bi bi-clock mr-1"></i>
                        Atualizado agora
                    </span>
                </div>
            </div>

            <div class="row" v-if="!carregando && item">
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card app-dashboard-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="dash-icon bg-primary mr-3">
                                    <i class="bi bi-car-front-fill"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Total de Veículos</small>
                                    <div class="dash-value">{{ $formatarNumero(item.total_carros) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card app-dashboard-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="dash-icon bg-success mr-3">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Disponíveis</small>
                                    <div class="dash-value">{{ $formatarNumero(item.carros_disponiveis) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card app-dashboard-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="dash-icon bg-warning mr-3">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Locados</small>
                                    <div class="dash-value">{{ $formatarNumero(item.carros_locados) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card app-dashboard-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="dash-icon bg-info mr-3">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Clientes</small>
                                    <div class="dash-value">{{ $formatarNumero(item.total_clientes) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-6 mb-4">
                    <div class="card app-dashboard-card dash-card-lg h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="dash-icon bg-dark mr-3">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Receita Total</small>
                                    <div class="dash-value">{{ $formatarMoeda(item.receita_total) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-6 mb-4">
                    <div class="card app-dashboard-card dash-card-lg h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="dash-icon bg-danger mr-3">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div>
                                    <small class="text-muted">Em Andamento</small>
                                    <div class="dash-value">{{ $formatarNumero(item.locacoes_andamento_qt) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!carregando && item && item.locacoes_proximas && item.locacoes_proximas.length" class="mb-4">
                <div class="card app-dashboard-table-card">
                    <div class="card-header">
                        <i class="bi bi-calendar-event mr-2"></i> Próximas Devoluções
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Carro</th>
                                        <th>Previsão</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="loc in item.locacoes_proximas" :key="loc.id">
                                        <td>
                                            <span class="table-cell-text">#{{ loc.id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-circle mr-2 text-muted"></i>
                                                <span>{{ loc.cliente && loc.cliente.nome ? loc.cliente.nome : '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-car-front mr-2 text-muted"></i>
                                                <span>{{ loc.carro && loc.carro.modelo && loc.carro.modelo.nome ? loc.carro.modelo.nome : '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="bi bi-calendar3 mr-1 text-muted"></i>
                                            {{ loc.data_final_previsto_periodo | formataDataGlobal }}
                                        </td>
                                        <td>
                                            <span class="app-dashboard-badge" :class="loc.data_final_realizado_periodo ? 'badge-success' : 'badge-warning'">
                                                {{ loc.data_final_realizado_periodo ? 'Finalizada' : 'Em andamento' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center py-5" v-if="carregando">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Carregando...</span>
                </div>
            </div>

            <div class="alert alert-danger mt-2" v-if="!carregando && erro">
                {{ erro }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                carregando: false,
                item: null,
                erro: '',
                urlBase: '/api/v1/dashboard',
            }
        },
        methods: {
            loadDashboard() {
                this.carregando = true
                this.erro = ''
                axios.get(this.urlBase)
                    .then(response => {
                        this.item = response.data
                    })
                    .catch(error => {
                        this.erro = error.response && error.response.data && error.response.data.message ? error.response.data.message : 'Erro ao carregar o painel.'
                    })
                    .finally(() => {
                        this.carregando = false
                    })
            },
            formatarNumero(valor) {
                return valor === undefined || valor === null ? '-' : valor
            },
            formatarMoeda(valor) {
                if (valor === undefined || valor === null) return '-'
                return Number(valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
            },
        },
        mounted() {
            this.loadDashboard()
        }
    }
</script>
