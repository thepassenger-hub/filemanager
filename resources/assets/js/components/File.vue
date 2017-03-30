<template>
    <li :class="{active: isActive}" @click="newActiveComponent">
        <span class="fa fa-file"></span>   
        <span v-if="!hideFile" class="files">{{ path | prettyPrint }}</span>
        <rename-file @selected="selected" v-if="renameFile && isActive" @renameFile="rename($event)" @hideForm="hideForm" :name="path | prettyPrint"></rename-file>
    </li>
</template>

<script>
    export default {
        props: ['path', 'showRenameInput'],
        data: function() {
            return {
                'isActive': false,
                'hideFile': false
            }
        },
        computed: {
            renameFile: function () {
                return this.showRenameInput;
            }
        },
        methods: {
            selected: function() {
                this.hideFile = true; 
                this.$emit('unMark');
            },
            rename: function(newName) {
                this.$emit('rename', newName); 
                this.isActive = false;
                this.$emit('hideForm');
            },
            hideForm: function(){
                this.isActive = false;
                this.$emit('hideForm'); 
                this.hideFile = false;
            },
            newActiveComponent: function() {
                this.$emit('selected');
                this.isActive = true
            }
        }
    }
</script>