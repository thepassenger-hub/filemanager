<template>

    <li class="columns">
        <form class="columns column is-12" id="newFileForm" @submit.prevent="$emit('createFile', newFileName)">
            <span class="column is-1" :class="className"></span>                                
            <input v-model="newFileName" @keyup.esc="$emit('clearNewFileForm')" @blur="$emit('clearNewFileForm')"
                class="column is-7 input"  placeholder="file.extension" v-focus>
        </form>
    </li>

</template>

<script>
    export default {
        props: ["type"],

        data: function(){
            return {
                newFileName: ''
            }
        },

        computed: {
            className() {
                return "fa fa-"+this.type;
            }
        }
    }
</script>