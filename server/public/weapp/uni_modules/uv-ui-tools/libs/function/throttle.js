"use strict";let t;exports.throttle=function(e,o=500,i=!0){i?t||(t=!0,"function"==typeof e&&e(),setTimeout((()=>{t=!1}),o)):t||(t=!0,setTimeout((()=>{t=!1,"function"==typeof e&&e()}),o))};
