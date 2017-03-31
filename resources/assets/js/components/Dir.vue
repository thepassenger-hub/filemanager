<template>
    <li @dblclick="$emit('open')" :class="{active: isActive}" @click="newActiveComponent">
        <span class="fa fa-folder"></span>
        <span class="dirs">{{ path | prettyPrint }}</span>
    </li>
</template>

<script>
    export default {
        props: ['path'],
        data() {
            return {
                isActive: false
            }
        },
        methods: {
            newActiveComponent() {
                this.$emit('selected');
                this.isActive = true
            }
        }
    }
</script>