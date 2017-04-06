<template>

    <li class="columns" @dblclick="$emit('open')" :class="{active: isActive}" @click="newActiveComponent">
        <span class="column is-1 fa fa-folder"></span>
        <span v-if="!hideFile" class="column is-8 dirs">{{ dir.path | prettyPrint }}</span>

        <rename-file v-if="renameFile && isActive" :name="dir.path | prettyPrint" 
            @selected="selected"  @renameFile="rename($event)" @hideForm="hideForm" >
        </rename-file>

        <span v-if="dir.path != '..'" class="column is-3" v-text="lastModified"></span>
    </li>

</template>

<script>
    export default {
        props: ['dir', 'showRenameInput'],

        data() {
            return {
                isActive: false,
                hideFile: false
            }
        },

        computed: {
            renameFile() {
                return this.showRenameInput;
            },

            lastModified() {
                let date = moment(this.dir.lastModified);
                let now = moment();
                if (now.year() !== date.year()) {
                    return date.format('D/M/gg');
                }
                else if (now.date() !== date.date() || now.month() !== date.month()) {
                    return date.format('MMM D');
                }
                else {
                    return date.format('H:mm');
                };
            }
        },

        methods: {
            newActiveComponent() {
                this.$emit('selected');
                this.isActive = true
            },

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
            }
        }
    }
</script>