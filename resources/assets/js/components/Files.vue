<template>
    <div class="columns">
        <div class="column is-2">
            <button @click="prevFolder()" :class="classObj" type="button" class="button is-dark">Back</button><br>
            <button @click="newFileFormVisible = true" type="button" class="button is-primary">Create File</button><br>
            <button @click="showNotification = true" type="button" class="button is-danger">Delete File</button><br>
            <button @click="showMovePanel = true; role = 'move'" type="button" class="button is-success">Move File</button>            <br>
            <button @click="showMovePanel = true; role = 'copy'" type="button" class="button is-warning">Copy File</button><br>
            <button @click="showRenameInput = true" type="button" class="button is-warning">Rename File</button><br>
            <button @click="" type="button" class="button is-default">Chmod</button><br>
            <chmod @chmod="chmod($event)"></chmod>

        </div>
        <div class="column is-one-third panel panel-default">
            <div class="panel-heading">
                <p>All Files</p>
                <deleteFileNotification v-if="showNotification" @deleteFile="deleteFile" @closeNotification="showNotification = false"></deleteFileNotification>
            </div>

            <div class="panel-block">
                <ul v-if="items">
                    <dir v-if="items.parentDir" :path="'..'" @open="fetchFiles(items.parentDir)"></dir>
                    <dir v-for="dir in items.directories" :path="dir" @open="fetchFiles(dir)"></dir>
                    <file @selected="fileSelected(file)" @unMark="unMarkAsSelected" @rename="renameFile($event, file)" @hideForm="showRenameInput = false"
                        v-for="file in items.files" :path="file" :show-rename-input="showRenameInput"></file>
                    <create-file v-if="newFileFormVisible" @createFile="createFile($event)" @clearNewFileForm="newFileFormVisible = false"></create-file>
                </ul>
            </div>
        </div>
        <moveFilePanel v-if="showMovePanel" :role="role" @copy="copyFile($event)" @move="moveFile($event)"></moveFilePanel>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                'items': [],
                'previous': [null],
                'currentSelected': '',
                'newFileFormVisible': false,
                'showNotification': false,
                'showMovePanel': false,
                "showRenameInput": false,
                'role': null
            }
        },
        created() { 
            this.fetchFiles();
        },
        methods: {
            currentPath(){
                return this.previous.slice(-1)[0];
            },
            fileSelected(file) {
                this.currentSelected = file;
                this.$children.forEach(
                    child => {if (child.path != this.currentSelected) child.isActive = false}
                );
            },
            getFileName(path){
                let fileName = path.split('/');
                fileName = fileName.splice(-1)[0];
                return fileName;
            },
            unMarkAsSelected(){
                $(".active").removeClass("active");
            },
            prevFolder(){
                this.previous.pop();
                let path = this.previous.slice(-1)[0];
                this.fetchFiles(path);
            },
            fetchFiles(path=null){
                var vm = this;
                axios.get('/api/files', {
                    params: {
                        'path': path,
                    }
                })
                    .then(function(response){
                        vm.items = response.data;
                        if (vm.previous.slice(-1)[0] !== path){
                            vm.previous.push(path);
                        };
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },

            createFile(name) {
                var vm = this;
                if (! name.trim()) {
                    return;
                }
                axios.put('/api/files', {
                    path: vm.currentPath(),
                    file: name
                })
                .then(function(response) {
                    console.log(response);
                    vm.fetchFiles(vm.currentPath());
                    vm.newFileFormVisible = false;
                })
                .catch(function(error) {
                    console.log(error);
                });
            },

            deleteFile() {
                var vm = this;
                axios.delete('/api/files', {
                    params: {
                        path: vm.currentSelected
                    }
                })
                .then(function(response){
                    console.log(response);
                    vm.fetchFiles(vm.currentPath());
                })
                .then(function(error){
                    console.log(error);
                });
                vm.showNotification = false;
            },

            moveFile(path) {
                var vm = this;
                let file = $('.active').attr('data-path');
                axios.patch('/api/files/move', {
                    path: path,
                    file: vm.currentSelected
                    })
                    .then(function(response) {
                        vm.showMovePanel = false;
                        vm.fetchFiles(vm.currentPath());
                    })
                    .then(function(error) {
                        console.log(error);
                    });
            },

            copyFile(path){
                var vm = this;
                axios.put('/api/files/copy', {
                    path: path,
                    file: vm.currentSelected
                    })
                    .then(function(response) {
                        vm.showMovePanel = false;
                        vm.fetchFiles(vm.currentPath());
                    })
                    .then(function(error) {
                        console.log(error);
                    });
            },

            renameFile(newName, path){
                var vm = this;
                axios.patch('/api/files/rename', {
                    file: path,
                    newName: newName
                })
                .then(function(response){
                    vm.fetchFiles(vm.currentPath());
                })
                .then(function(error){
                    console.log(error)
                })
            },
            chmod(value) {
                 
            }
        },
        computed: {
            classObj: function(){
                return {
                    'is-disabled': this.previous.slice(-1)[0] === null
                };
            }
        },
        
    }
</script>