<template>
    <div class="columns">

        <div class="column is-2">
            <button @click="showCreate = !showCreate" class="button is-primary">Create</button><br>

            <transition name="fade">
                <button  v-if="showCreate" @click="newFileFormVisible = true; type='file'" type="button" class="button is-primary">File</button>
            </transition>

            <transition name="fade">
                <button v-if="showCreate" @click="newFileFormVisible = true; type='folder'" type="button" class="button is-primary">Folder</button>
            </transition>
            
            <button @click="showMovePanel = true; role = 'move'" type="button" class="button is-success" :class="isDisabled">Move</button><br>
            <button @click="showMovePanel = true; role = 'copy'" type="button" class="button is-dark" :class="isDisabled">Copy</button><br>
            <button @click="showRenameInput = true" type="button" class="button is-black" :class="isDisabled">Rename</button><br>
            <button @click="showNotification = true" type="button" class="button is-danger" :class="isDisabled">Delete</button><br>            
            <button @click="showChmodInput = !showChmodInput" type="button" class="button is-default" :class="isDisabled">Chmod</button><br>

            <notify-success @close="showSuccess = false" v-show="showSuccess"></notify-success>
            <notify-error @close="showError = false" :error-message="errorMessage" v-show="showError"></notify-error>

            <transition name="fade">            
                <chmod @error="showNotifyError($event)" v-if="showChmodInput" @close="showChmodInput = false" @chmod="chmod($event)"></chmod>
            </transition>

        </div>

        <div class="column panel panel-default" :class="isTen">

            <div class="panel-heading">
                <div class="field has-addons">
                    <p class="control">
                        <a @click="prevFolder()" :class="previousObj" type="button" class="button is-dark">
                            <span class="fa fa-arrow-left"></span>
                        </a>
                    </p>
                    <p class="control">
                        <a @click="nextFolder()" :class="nextObj" type="button" class="button is-dark">
                            <span class="fa fa-arrow-right"></span>                    
                        </a>   
                    </p>
                </div>

                <path-nav @clicked="fetchFiles($event)" :current-path="currentPath"></path-nav>

                <transition name="fade">    
                    <deleteFileNotification v-if="showNotification" @deleteFile="deleteFile" @closeNotification="showNotification = false"></deleteFileNotification>
                </transition>        

            </div> <!-- End of panel-heading -->

            <div class="panel-block">
                <ul v-if="files || dirs">

                    <dir v-if="parentDir" :dir="{path: '..', lastModified: null}" @selected="fetchFiles(parentDir)" ></dir>

                    <dir v-for="dir in dirs" :dir="dir" @open="fetchFiles(dir.path)" :show-rename-input="showRenameInput" 
                        @selected="fileSelected(dir)" @unMark="unMarkAsSelected" @rename="renameFile($event, dir)" 
                        @hideForm="showRenameInput = false">
                    </dir>

                    <file v-for="file in files" :file="file" :show-rename-input="showRenameInput" @open="openFile" 
                        @selected="fileSelected(file)" @unMark="unMarkAsSelected" @rename="renameFile($event, file)" 
                        @hideForm="showRenameInput = false">
                    </file>

                    <create-file v-if="newFileFormVisible" :type="type" @createFile="createFile($event)" @clearNewFileForm="newFileFormVisible = false"></create-file>

                </ul>
            </div> <!--End of panel-body-->

        </div><!-- End of panel -->

        <transition name="fade">            
            <moveFilePanel v-if="showMovePanel" :role="role" @close="showMovePanel = false;" @copy="copyFile($event)" @move="moveFile($event)"></moveFilePanel>
        </transition>

    </div>
</template>

<script>
    import File from '../models/File.js';
    import Base from '../models/Base.js';
    import Dir from '../models/Dir.js';

    export default {
        data: function(){
            return {
                currentPath: null,
                files: [],
                dirs: [],
                parentDir: false,
                type: null,
                showCreate: false,
                previous: [null],
                next: [null],
                currentSelected: null,
                newFileFormVisible: false,
                showNotification: false,
                showMovePanel: false,
                showRenameInput: false,
                showChmodInput: false,                
                showError: false,
                showSuccess: false,
                errorMessage: '',
                role: null
            }
        },

        created() { 
            this.fetchFiles();
        },

        methods: {

            fileSelected(file) {
                this.currentSelected = file;
                this.$children.forEach(
                    child => {
                        if (child.path != this.currentSelected) child.isActive = false
                        }
                );
            },

            deselectFile() {
                this.currentSelected = null;
                this.$children.forEach(
                    child => child.isActive = false
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
                this.next.push(this.previous.pop());
                let path = this.previous.slice(-1)[0];
                this.fetchFiles(path);
            },

            nextFolder(){
                let path = this.next.pop();
                this.previous.push(path);
                this.fetchFiles(path);
            },

            fetchFiles(path=null){
                var vm = this;

                axios.get('/api/files', {
                        params: {
                            'path': path,
                        }
                    })
                    .then(response => {
                        let newFiles = [];
                        let newDirs = [];
                        // Create new File and Folder objects and store them in an array.
                        response.data.files.forEach(file => newFiles.push(new File(file)));  
                        response.data.dirs.forEach(dir => newDirs.push(new Dir(dir)));
                        vm.files = newFiles;
                        vm.dirs = newDirs;
                        vm.parentDir = response.data['parentDir'];                    
                        if (vm.previous.slice(-1)[0] !== path){
                            vm.previous.push(path);
                        };
                        vm.currentPath = response.data.path;
                    })
                    .catch(error => vm.showNotifyError(error.response.data));

                this.deselectFile();
                },

            createFile(name) {
                var vm = this;
                if (! name.trim()) {
                    return;
                }
                let item = new Base(name, vm.currentPath, vm.type);
                item.create()
                    .then(response => {
                        vm.fetchFiles(vm.currentPath);
                        vm.newFileFormVisible = false;
                    })
                    .catch(error => vm.showNotifyError(error));
            },

            deleteFile() {
                var vm = this;
                this.currentSelected.delete()
                    .then(response => vm.fetchFiles(vm.currentPath))
                    .catch(error => vm.showNotifyError(error));
                vm.showNotification = false;
            },

            moveFile(path) {
                var vm = this;
                this.currentSelected.move(path)
                    .then(response => {
                        vm.showMovePanel = false;
                        vm.fetchFiles(vm.currentPath);
                    })
                    .catch(error => showNotifyError(error));
            },

            copyFile(path){
                var vm = this;
                this.currentSelected.copy(path)
                    .then(response => {
                        vm.showMovePanel = false;
                        vm.fetchFiles(vm.currentPath);
                    })
                    .catch(error => showNotifyError(error));
            },

            renameFile(newName){
                var vm = this;
                this.currentSelected.rename(newName)
                    .then(response => vm.fetchFiles(vm.currentPath))
                    .catch(error => showNotifyError(error));
            },

            chmod(value) {
                let vm = this;
                this.currentSelected.chmod(value)
                    .then(response => {
                        vm.showNotifySuccess();
                    })
                    .catch(error => showNotifyError(error));

                vm.showChmodInput = false;
            },

            openFile() {
                let vm = this;                
                this.currentSelected.open()
                    .then(response => vm.showNotifySuccess())
                    .catch(error => vm.showNotifyError(error));
            },

            showNotifySuccess(){
                let vm = this;
                this.showSuccess = true;
                setTimeout(function(){
                    vm.showSuccess = false;
                }, 5000);
            },
            
            showNotifyError(error){
                let vm = this;
                this.errorMessage = error;
                this.showError = true;
                setTimeout(function(){
                    vm.showError = false;
                }, 15000);
            }
        },

        computed: {
            previousObj(){
                return {
                    'is-disabled': this.previous.slice(-1)[0] === null
                };
            },

            isTen(){
                return {
                    'is-10': this.showMovePanel === false,
                    'is-half': this.showMovePanel === true
                }
            },

            nextObj(){
                return {
                    'is-disabled': this.next.slice(-1)[0] === null
                };
            },

            isDisabled(){
                return {'is-disabled': this.currentSelected === null}
            }

        },
    }
</script>