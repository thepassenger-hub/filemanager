<template>
    <div class="notification is-success">
        <button class="delete" @click="$emit('close')"></button>
        The acion performed was successful.
    </div>
</template>

<script>
    export default {

    }
</script>