require('../css/global.scss');

require('bootstrap');

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
var $ = require('jquery');
window.$ = $;
window.jQuery = $;

require('./competition_detail.js');
require('./rating.js');