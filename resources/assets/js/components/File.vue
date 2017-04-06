<template>

    <li :class="{active: isActive}" class="columns" @click="newActiveComponent" @dblclick.prevent="$emit('open')">
        <span class="column is-1 fa fa-file"></span>   
        <span v-if="!hideFile" class="column is-7 files">{{ file.path | prettyPrint }}</span>

        <rename-file v-if="renameFile && isActive" :name="file.path | prettyPrint" 
            @selected="selected"  @renameFile="rename($event)" @hideForm="hideForm"  class="column is-two-thirds">
        </rename-file>

        <span class="column is-2" v-text="size"></span>
        <span class="column is-2" v-text="lastModified"></span>
        
    </li>

</template>

<script>
    export default {

        props: ['file', 'showRenameInput'],

        data: function() {
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
                let date = moment(this.file.lastModified);
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
            },

            size() {
                let bytes = this.file.size;
                const sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
                if (bytes === 0) return '0';
                const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10);
                if (i === 0) return `${bytes} ${sizes[i]}`;
                return `${(bytes / (1024 ** i)).toFixed(1)} ${sizes[i]}`
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