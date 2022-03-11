// assets/app.js
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
// import './styles/app.css';
//
// // start the Stimulus application
import './bootstrap';
import './styles/app.css';
import './styles/custom.css';
import './styles/_homepage.css';
import './styles/navbar.css';
import './styles/studies-services.css';
import './styles/blog.css';
import './styles/beauty_science.scss';
import './styles/story.scss';
import './styles/sidebar.scss';


const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');