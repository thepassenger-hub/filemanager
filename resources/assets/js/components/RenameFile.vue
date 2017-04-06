<template>

        <input  v-model="newFileName" class="column is-8 input rename_inputs" @blur="$emit('hideForm')" 
            @keyup.esc="$emit('hideForm')" @click="$emit('selected')" @keyup.enter="$emit('renameFile', newFileName)" v-focus>

</template>


<script>
    export default {
        data: function(){
            return {
                newFileName: this.name
            }
        },

        props: ["name"],
        
        created: function() {
            this.$emit('selected');
        }
    }
</script>