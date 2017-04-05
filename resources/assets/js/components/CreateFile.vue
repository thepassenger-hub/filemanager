<template>
    <li class="columns">
        <form class="columns column is-12" @submit.prevent="$emit('createFile', newFileName)" id="newFileForm">
            <span class="column is-1" :class="className"></span>                                
            <input @keyup.esc="$emit('clearNewFileForm')" v-focus @blur="$emit('clearNewFileForm')"
             class="column is-7 input" v-model="newFileName" placeholder="file.extension" >
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
        created(){
            console.log(this)
        },
        computed: {
            className() {
                return "fa fa-"+this.type;
            }
        }
    }
</script>