<template>
    <li class="columns" id="file-to-upload">
        <span class="column is-1 fa fa-file"></span>   
        <span class="column is-5 files">{{ file.name }}</span>
        <span class="column is-4">
            <button class="button is-small" id="upload-file-button" type="button" @click="$emit('upload')">Upload Here</button>
        </span>
        <span class="column is-2">
            <button class="button is-small" id="upload-file-abort" type="button" @click="$emit('abort')">Abort</button>
        </span>
    </li>
</template>

<script>
    export default {
        props: ["file"]
    }
</script>