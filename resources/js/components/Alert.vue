<template>

    <div :class="estilo" role="alert">
        {{ titulo }}
        
        <span v-if="detalhes.data.message"><hr> {{ detalhes.data.message }}</span>
        <!-- optional chaining -> detalhes?.data?.data?.id -->
       
        <!-- Caso dê erro -->
        <ul v-if="detalhes.data.errors">
            <li v-for="e, key in detalhes.data.errors" :key="key">
                {{ e[0] }}
            </li>
        </ul>
    </div>

</template>

<script>
    export default {
        props: ['tipo', 'detalhes', 'titulo'],
        computed:{
            estilo(){
                return 'alert alert-'+this.tipo
            }
        }
    }
</script>
