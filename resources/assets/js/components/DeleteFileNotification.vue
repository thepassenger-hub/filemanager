<template>
    <div class="notification is-danger">
        This action is NOT reversible.
        Are you sure you want to delete this file?
        <button class="button is-danger" @click="deleteFile" >Yes</button>        
        <button class="button is-primary" @click="closeNotification">No</button>
    </div>
</template>

<script>
    export default {
        methods: {
            closeNotification: function(){
                this.$emit('closeNotification');
            },

            deleteFile: function() {
                this.$emit('deleteFile');
            }
        }
    }
</script>