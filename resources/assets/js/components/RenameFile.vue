<template>
        <input v-focus class="input rename_inputs" @blur="$emit('hideForm')"
         @keyup.esc="$emit('hideForm')" @click="$emit('selected')"  
         @keyup.enter="$emit('renameFile', newFileName)" 
         v-model="newFileName">
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