<template>
    <div class="card app-card" :class="filter ? 'app-card--filter' : ''">
        <div class="card-header">{{ titulo }}</div>
        <div class="card-body">
            <slot name="conteudo"></slot>
        </div>
        <div class="card-footer" v-if="$slots.rodape">
            <slot name="rodape"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            titulo: String,
            filter: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
