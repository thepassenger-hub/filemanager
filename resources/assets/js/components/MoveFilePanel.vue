<template>
        <div class="column is-one-third ">
            <!--<button v-if="role === 'move'" type="button" class="fa fa-arrows-h" @click="$emit('move', previous.slice(-1)[0])"></button>  
            <button v-if="role === 'copy'" type="button" class="fa fa-arrows-h" @click="$emit('copy', previous.slice(-1)[0])"></button>                      -->
            
            <i v-if="role === 'move'" type="button" class="fa fa-arrows-h" @click="$emit('move', previous.slice(-1)[0])"></i>
            <i v-if="role === 'copy'" type="button" class="fa fa-arrows-h" @click="$emit('copy', previous.slice(-1)[0])"></i>
            
            <div id="move_file_panel" class="panel panel-default">
                <div class="panel-heading">
                    <p>{{ role | capitalize }} your file or folder here.</p>
                    <button @click="$emit('close')" class="button is-black">Close</button>
                </div>
                <div class="panel-block">
                    <ul v-if="items">
                        <li class="columns" v-if="items.parentDir" @click="fetchFiles(items.parentDir)">
                            <span class="column is-1 fa fa-folder secondary-icon"></span>   
                            <span class="column is-11">..</span>                             
                        <li class="columns" v-for="item in items.dirs" @click="fetchFiles(item.path)">
                            <span class="column is-1 fa fa-folder secondary-icon"></span>
                            <span class="column is-11">{{ item.path | prettyPrint }}</span>
                        </li>
                        <li class="columns" v-for="item in items.files" :data-path="item.path">
                            <span class="column is-1 fa fa-file secondary-icon"></span>                                
                            <span class="column is-11">{{ item.path | prettyPrint }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        data: function(){
            return {
                'items': [],
                'previous': [null],
            }
        },
        props: ['role'],
        created() {
            this.fetchFiles();  
        },
        methods: {
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
                },
        }
    }
</script>            