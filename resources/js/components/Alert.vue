<template>
    <div :class="estilo" role="alert">
        <strong>{{ titulo }}</strong>
        <hr v-if="detalhes.mensagem || detalhes.dados">
        <p v-if="detalhes.mensagem" class="mb-1">{{ detalhes.mensagem }}</p>
        <ul v-if="detalhes.dados" class="mb-0">
            <li v-for="e, key in detalhes.dados" :key="key">
                {{ e[0] }}
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: ['tipo', 'detalhes', 'titulo'],
        computed: {
            estilo() {
                return 'alert app-alert alert-' + this.tipo
            }
        }
    }
</script>
