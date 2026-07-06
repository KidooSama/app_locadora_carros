<template>
    <div class="form-group mb-3">
        <label :for="id" class="form-label-custom d-block">{{ titulo }}</label>
        <slot></slot>
        <div v-if="helpText" :id="idHelp" class="form-text text-muted">{{ helpText }}</div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'titulo', 'idHelp', 'helpText']
    }
</script>
