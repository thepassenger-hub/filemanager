<template>
    <li @click="$emit('open')">
        <span class="fa fa-folder"></span>
        <span class="dirs">{{ path | prettyPrint }}</span>
    </li>
</template>

<script>
    export default {
        props: ['path']
    }
</script>