<template>
        <div class="columns">
            <div class="column is-2">
                <button @click="prevFolder()" :class="classObj" type="button" class="button is-dark">Back</button><br>
                <button @click="newFileFormVisible = true" type="button" class="button is-primary">Create File</button><br>
                <button @click="showNotification = true" type="button" class="button is-danger">Delete File</button><br>
                <button @click="showMovePanel = true; role = 'move'" type="button" class="button is-success">Move File</button> <br>
                <button @click="showMovePanel = true; role = 'copy'" type="button" class="button is-warning">Copy File</button><br>
                <button @click="showRename" type="button" class="button is-warning">Rename File</button><br>  
                <button @click="" type="button" class="button is-default">Chmod</button><br>                
                              
            </div>
            <div class="column is-one-third panel panel-default">
                <div class="panel-heading">
                    <p>All Files</p>
                    <deleteFileNotification v-if="showNotification" @deleteFile="deleteFile" @closeNotification="showNotification = false"></deleteFileNotification>
                </div>

                <div class="panel-block">
                    <ul v-if="items">
                        <li v-if="items.parentDir" @click="fetchFiles(items.parentDir)">
                            <span class="fa fa-folder"></span>                                
                            ..
                        <li v-for="item in items.directories" @click="fetchFiles(item)">
                            <span class="fa fa-folder"></span>
                            <span class="dirs">{{ item | prettyPrint }}</span>
                        </li>
                        <li @click="markAsSelected" v-for="item in items.files" :data-path="item">
                            <span class="fa fa-file"></span>   
                            <span class="items">{{ item | prettyPrint }}</span>
                            <input class="input rename_inputs" v-focus @blur="abortRename" @click="unMarkAsSelected" @keyup.esc="abortRename" @keyup.enter="renameFile" v-model="newName">
                        </li>
                        <li>
                            <form v-if="newFileFormVisible" @submit.prevent="createFile()" id="newFileForm">
                                <span class="fa fa-file"></span>                                
                                <input @keyup.esc="clearNewFileForm" v-focus @blur="clearNewFileForm()" name="fileName" class="input" v-model="newFileName" placeholder="file.extension" >
                            </form>
                        </li>
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
                'newFileFormVisible': false,
                'newFileName': '',
                'showNotification': false,
                'showMovePanel': false,
                "showRenameInput": false,
                'newName': '',
                'role': null
            }
        },
        created() { 
            this.fetchFiles();
        },
        methods: {
            currentPath: function(){
                return this.previous.slice(-1)[0];
            },
            getFileName: function(path){
                let fileName = path.split('/');
                fileName = fileName.splice(-1)[0];
                return fileName;
            },
            markAsSelected: function(event) {
                this.unMarkAsSelected();
                var item;
                if (event.target.tagName == 'INPUT') {
                    return;
                }
                else if (event.target.tagName != 'LI') {
                    item = $(event.target).parent();
                }
                else {
                    item = $(event.target);
                }
                item.addClass("active");
                this.newName = this.getFileName(
                    item.attr('data-path')
                );
            },
            unMarkAsSelected: function(){
                $(".active").removeClass("active");
            },
            prevFolder: function(){
                this.previous.pop();
                let path = this.previous.slice(-1)[0];
                this.fetchFiles(path);
            },
            clearNewFileForm: function(){
                this.newFileName = '';
                this.newFileFormVisible = false;
            },
            showRename: function(){
                let file = $('.active');
                file.children('.items').hide();
                file.children('input').show();
                $('.active').removeClass('active');
                file.children('input').focus();
            },
            abortRename: function(event){
                let file = $(event.target).parent();
                file.children('.items').show();
                file.children('input').hide();
                this.unMarkAsSelected();
            },
            fetchFiles: function(path=null){
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
                vm.unMarkAsSelected();
                },

            createFile: function() {
                var vm = this;
                let fileName = vm.newFileName;
                if (! fileName.trim()) {
                    return;
                }
                let currentPath = vm.previous.slice(-1)[0];
                axios.put('/api/files', {
                    path: currentPath,
                    file: vm.newFileName
                })
                .then(function(response) {
                    console.log(response);
                    vm.fetchFiles(currentPath);
                    vm.clearNewFileForm();
                })
                .catch(function(error) {
                    console.log(error);
                });
            },

            deleteFile: function() {
                var vm = this;
                let path = $('.active').attr('data-path');
                let currentPath = vm.previous.slice(-1)[0];
                axios.delete('/api/files', {
                    params: {
                        path: path
                    }
                })
                .then(function(response){
                    console.log(response);
                    vm.fetchFiles(currentPath);
                })
                .then(function(error){
                    console.log(error);
                });
                vm.showNotification = false;
            },

            moveFile: function(path) {
                var vm = this;
                let file = $('.active').attr('data-path');
                axios.patch('/api/files/move', {
                    path: path,
                    file: file
                    })
                    .then(function(response) {
                        vm.showMovePanel = false;
                        vm.fetchFiles(vm.currentPath());
                    })
                    .then(function(error) {
                        console.log(error);
                    });
            },

            copyFile: function(path){
                var vm = this;
                let file = $('.active').attr('data-path');
                axios.put('/api/files/copy', {
                    path: path,
                    file: file
                    })
                    .then(function(response) {
                        vm.showMovePanel = false;
                        vm.fetchFiles(vm.currentPath());
                    })
                    .then(function(error) {
                        console.log(error);
                    });
            },

            renameFile: function(event){
                var vm = this;
                let file = $(event.target).parent().attr('data-path');
                axios.patch('/api/files/rename', {
                    file: file,
                    newName: vm.newName
                })
                .then(function(response){
                    vm.fetchFiles(vm.currentPath());
                    vm.abortRename(event);
                    
                })
                .then(function(error){
                    console.log(error)
                })
            }
        },
        
        computed: {
            classObj: function(){
                return {
                    'is-disabled': this.previous.slice(-1)[0] === null
                };
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