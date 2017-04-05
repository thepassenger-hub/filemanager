<template>
    <div class="message is-danger">
        <div class="message-header">
            Careful.
        </div>
        <div class="message-body">
            <p>
                This action is NOT reversible.
                Are you sure you want to delete this file?
            </p>
            <button class="button is-danger" @click="deleteFile" >Yes</button>        
            <button class="button is-primary" id="delete-file-button" @click="closeNotification">No</button>
        </div>
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