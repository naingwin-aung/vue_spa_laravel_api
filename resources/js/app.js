require('./bootstrap');

window.Vue = require('vue').default;
import Form from 'vform'
import Swal from 'sweetalert2'
import VueProgressBar from 'vue-progressbar'

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

const options = {
    color: '#675cd8',
    failedColor: '#874b4b',
    thickness: '5px',
  }
   
  Vue.use(VueProgressBar, options)

window.Form = Form
window.Swal = Swal
window.Toast = Toast

Vue.component('Product', require('./components/Product.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));

const app = new Vue({
    el: '#app',
});
