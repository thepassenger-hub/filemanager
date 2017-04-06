<template>

    <div class="field has-addons">
        <p v-for="path in paths" class="control">
            <a @click="$emit('clicked', path)" class="button is-small is-dark" :class="classObj(path)" >
                {{ path | prettyfy | capitalize }}
            </a>
        </p>
    </div>
    
</template>

<script>
    export default {
        props: ['currentPath'],
        computed: {
            paths() {
                if (this.currentPath) {
                    let subpath = this.currentPath.split('/');
                    if (subpath.slice(-1)[0] === '') return ['/'];
                    let out = [];
                    subpath.forEach(path => {
                        if (out.slice(-1)[0] !== undefined && out.slice(-1)[0] !== '/') {
                            out.push(out.slice(-1)[0] + '/' + path);
                        } else if (out.slice(-1)[0] !== undefined) {
                            out.push(out.slice(-1)[0] + path);                            
                        }
                         else {
                            out.push('/');
                        };
                    });
                    return out;
                }
                return ['/'];
            },
        },

        methods: {
            classObj(path){
                if (path === this.paths.slice(-1)[0]) return {'is-active': true};
            }
        },
            
        filters: {
            prettyfy(path){
                path = path.split('/').splice(-1)[0];
                path = path ? path : '/';
                return path;
            }
        }

    }
</script>