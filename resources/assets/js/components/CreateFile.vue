<template>
    <li>
        <form @submit.prevent="$emit('createFile', newFileName)" id="newFileForm">
            <span class="fa fa-file"></span>                                
            <input @keyup.esc="$emit('clearNewFileForm')" v-focus @blur="$emit('clearNewFileForm')" class="input" v-model="newFileName" placeholder="file.extension" >
        </form>
    </li>
</template>

<script>
    export default {
        data: function(){
            return {
                'newFileName': ''
            }
        },
        directives: {
            focus: {
                inserted: function (el) {
                    el.focus()
                }
            },
        }
    }
</script>