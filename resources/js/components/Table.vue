<template>
    <div class="app-table-wrapper">
        <div class="table-responsive">
            <table class="table table-hover mb-0 app-table">
                <thead>
                    <tr>
                        <th v-for="t, key in titulos" :key="key" scope="col" :class="headerClass(key, t.tipo)">{{ t.titulo }}</th>
                        <th v-if="visualizar.visivel || atualizar.visivel || remover.visivel || (finalizar && finalizar.visivel)" class="text-center col-actions" scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="!dadosFiltrados.length">
                        <td :colspan="Object.keys(titulos).length + (visualizar.visivel || atualizar.visivel || remover.visivel || (finalizar && finalizar.visivel) ? 1 : 0)">
                            <div class="app-empty-state">
                                <i class="bi bi-inbox app-empty-state__icon"></i>
                                <p>Nenhum registro encontrado.</p>
                            </div>
                        </td>
                    </tr>
                    <tr v-for="obj, k in dadosFiltrados" :key="k">
                        <td v-for="d, chaveValor in obj" :key="chaveValor" :class="cellClass(chaveValor, titulos[chaveValor].tipo)">
                            <span v-if="titulos[chaveValor].tipo == 'text'" class="table-cell-text">{{ formatText(d, chaveValor) }}</span>
                            <span v-if="titulos[chaveValor].tipo == 'fk'" class="table-cell-text">{{ d ? d[titulos[chaveValor].chave] : '—' }}</span>
                            <span v-if="titulos[chaveValor].tipo == 'bool'">
                                <span :class="d ? 'badge badge-success badge-status' : 'badge badge-light badge-status text-muted'">
                                    <i :class="d ? 'bi bi-check-circle-fill' : 'bi bi-x-circle'"></i>
                                    {{ d ? 'Sim' : 'Não' }}
                                </span>
                            </span>
                            <span v-if="titulos[chaveValor].tipo == 'img'" class="table-img-cell">
                                <img
                                    v-if="d"
                                    :src="storageUrl(d)"
                                    class="table-img table-img--logo"
                                    :alt="'Logo'"
                                    @error="onImgError"
                                >
                                <span v-else class="table-img-placeholder">
                                    <i class="bi bi-image"></i>
                                </span>
                            </span>
                            <span v-if="titulos[chaveValor].tipo == 'data'" class="table-cell-date">
                                <i class="bi bi-clock text-muted mr-1"></i>{{ d | formataDataTempoGlobal }}
                            </span>
                            <span v-if="titulos[chaveValor].tipo == 'date'" class="table-cell-date">
                                <i class="bi bi-calendar3 text-muted mr-1"></i>{{ d | formataDataGlobal }}
                            </span>
                            <span v-if="titulos[chaveValor].tipo == 'status'">
                                <span :class="statusBadgeClass(d)">{{ d || '—' }}</span>
                            </span>
                            <span v-if="titulos[chaveValor].tipo == 'km'">
                                <span :class="statusBadgeClass(d)">{{  d | formataKmGlobal }}</span>
                            </span>
                            <span v-if="titulos[chaveValor].tipo == 'money'" class="table-cell-money">
                                {{ formatMoney(d) }}
                            </span>
                        </td>
                        <td v-if="visualizar.visivel || atualizar.visivel || remover.visivel || (finalizar && finalizar.visivel)" class="text-center col-actions">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm btn-action-toggle dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <button v-if="visualizar.visivel" class="dropdown-item" type="button" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(obj.id)">
                                        <i class="bi bi-eye dropdown-icon"></i> Visualizar
                                    </button>
                                    <button v-if="atualizar.visivel && obj.status !== 'Finalizada'" class="dropdown-item" type="button" :data-toggle="atualizar.dataToggle" :data-target="atualizar.dataTarget" @click="setStore(obj.id)">
                                        <i class="bi bi-pencil-square dropdown-icon"></i> Atualizar
                                    </button>
                                    <div v-if="(remover.visivel || (finalizar && finalizar.visivel)) && (visualizar.visivel || atualizar.visivel)" class="dropdown-divider"></div>
                                    <button v-if="finalizar && finalizar.visivel && obj.status !== 'Finalizada'" class="dropdown-item text-success" type="button" :data-toggle="finalizar.dataToggle" :data-target="finalizar.dataTarget" @click="setStore(obj.id)">
                                        <i class="bi bi-check2-circle dropdown-icon"></i> Finalizar
                                    </button>
                                    <button v-if="remover.visivel" class="dropdown-item text-danger" type="button" :data-toggle="remover.dataToggle" :data-target="remover.dataTarget" @click="setStore(obj.id)">
                                        <i class="bi bi-trash dropdown-icon"></i> Remover
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['dados', 'titulos', 'visualizar', 'atualizar', 'remover', 'finalizar', 'url'],
        computed: {
            dadosFiltrados() {
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = this.dados.map(item => {
                    let itemFiltrado = {}
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo]
                    })
                    return itemFiltrado
                })
                return dadosFiltrados
            }
        },
        methods: {
            storageUrl(path) {
                if (!path) return ''
                if (path.startsWith('http://') || path.startsWith('https://')) return path
                if (path.startsWith('/storage/')) return path
                if (path.startsWith('storage/')) return '/' + path
                return '/storage/' + path.replace(/^\/+/, '')
            },
            onImgError(e) {
                e.target.style.display = 'none'
                if (e.target.nextElementSibling) return
                let placeholder = document.createElement('span')
                placeholder.className = 'table-img-placeholder'
                placeholder.innerHTML = '<i class="bi bi-image"></i>'
                e.target.parentNode.appendChild(placeholder)
            },
            headerClass(key, tipo) {
                if (key === 'id') return 'col-id'
                if (tipo === 'img') return 'col-img text-center'
                if (tipo === 'bool') return 'text-center'
                return ''
            },
            cellClass(key, tipo) {
                if (key === 'id') return 'col-id'
                if (key === 'placa') return 'col-placa'
                if (tipo === 'img') return 'col-img text-center'
                if (tipo === 'bool') return 'text-center'
                if (tipo === 'data' || tipo === 'date') return 'col-date'
                return ''
            },
            formatText(value, key) {
                if (value === null || value === undefined || value === '') return '—'
                if (key === 'id') return value
                return value
            },
            formatMoney(value) {
                if (value === null || value === undefined || value === '') return '—'
                return 'R$ ' + Number(value).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            },
            statusBadgeClass(status) {
                let base = 'badge badge-status '
                if (status === 'Finalizada') return base + 'badge-secondary'
                if (status === 'Em andamento') return base + 'badge-primary'
                return base + 'badge-light text-muted'
            },
            setStore(id) {
                this.$store.commit('limparTransacao')
                this.$store.state.item = {}
                axios.get(`${this.url}/${id}`)
                    .then(response => {
                        this.$store.state.item = response.data
                        this.$emit('load-marca-options')
                        this.$emit('load-modelo-options')
                        
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            }
        },
    }
</script>
