/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import $ from 'jquery'
let $ = require('jquery')
import './styles/app.css';
// start the Stimulus application
//import './bootstrap';
//console.log('motovelomotahelico');
require('select2')
//require('select2/dist/css/select2.min.css')
$('select').select2()
let $contactButton = $('#contactButton')
$contactButton.click(e => {
    e.preventDefault();
    $('#contactForm').slideDown();
    $contactButton.slideUp();
} )