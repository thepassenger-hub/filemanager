<template>

    <input v-focus v-model="value" class="input" type="text" maxlength="3" 
        placeholder="000-777" @keyup.esc="$emit('close')" @blur="$emit('close')" @keyup.enter="validate()">
</template>

<script>
    export default {
        data: function(){
            return {
                value: ''
            }
        },
        
        methods: {
            validate() {
                if (/^[0-7]{3}$/.test(this.value)) {
                    this.$emit('chmod', this.value);
                    this.value = '';
                }
                else {
                    this.$emit('error', `
                        The value can only be a 3 digit number from 0 to 7.
                        i.e. 000-777
                    `);
                }
            }
        },
    }
</script>