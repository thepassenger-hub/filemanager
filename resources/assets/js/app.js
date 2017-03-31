
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

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
Vue.filter( 'prettyPrint', function(path){
            path = path.split('/');
            path = path.splice(-1)[0];
            return path;
        }
)
Vue.directive('focus', {
  // When the bound element is inserted into the DOM...
  inserted: function (el) {
    // Focus the element
    el.focus()
  }
})

const app = new Vue({
    el: '#app',
    directives: {
        focus: {
            inserted: function (el) {
                el.focus()
            }
        },
    }

});


