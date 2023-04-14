/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

// start the Stimulus application
import "./bootstrap";

import Vue from "vue";
// import VueRouter from 'vue-router';

// // app specific
// import router from './vue/router/';
// import app from './vue/app';

// Vue.use(VueRouter);

Vue.component("fruits", {
  data: function () {
    return {
      fruits: [{ id: 1 }],
    };
  },
  template: `<div class="fruits-list">
    <h3>{{ id }}</h3>
  </div>`,
});
// // bootstrap the app
let demo = new Vue({
  el: "#vueApp",
  // router,
  // template: '<app/>',
  // components: { fruits }
});
