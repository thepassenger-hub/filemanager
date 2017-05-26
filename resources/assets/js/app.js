require('./bootstrap');

window.moment = require('moment');

Vue.component('pathNav', require('./components/PathNav.vue'));
Vue.component('files', require('./components/Files.vue'));
Vue.component('file', require('./components/File.vue'));
Vue.component('dir', require('./components/Dir.vue'));
Vue.component('notifyError', require('./components/NotifyError.vue'));
Vue.component('notifySuccess', require('./components/NotifySuccess.vue'));
Vue.component('chmod', require('./components/Chmod.vue'));
Vue.component('renameFile', require('./components/RenameFile.vue'));
Vue.component('createFile', require('./components/CreateFile.vue'));
Vue.component('deleteFileNotification', require('./components/DeleteFileNotification.vue'));
Vue.component('moveFilePanel', require('./components/MoveFilePanel.vue'));
Vue.component('uploadFile', require('./components/UploadFile.vue'));


Vue.filter( 'prettyPrint', function(path){
            path = path.split('/');
            path = path.splice(-1)[0];
            return path;
        }
);
Vue.filter( 'capitalize', function(value){
            if (!value) return ''
            value = value.toString()
            return value.charAt(0).toUpperCase() + value.slice(1)
        }
);

Vue.directive('focus', {
  inserted: function (el) {
    el.focus()
  }
})

const app = new Vue({
    el: '#app',
});


