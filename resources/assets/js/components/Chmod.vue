<template>
    <input v-model="value" class="input" type="text" maxlength="3" placeholder="000-777" @keyup.enter="validate()">
</template>
<script>
    export default {
        data: function(){
            return {
                value: '644'
            }
        },
        methods: {
            validate() {
                if (/^[0-7]{3}$/.test(this.value)) {
                    this.$emit('chmod', this.value);
                }
            }
        }

    }
</script>