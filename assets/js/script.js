window.addEventListener('DOMContentLoaded', () => {
  import('/wp-content/themes/custom-theme/assets/js/min/core-function.js')
  .then((module) => {
    document.body.addEventListener('afterStyleLoaded', () => {
      module.AppendJs( ['body'] );
      module.LazyImage();
    });

    module.AppendStyle( ['body'] );

    window.addEventListener('scroll', event => {
      module.LazyImage();
    });
  })
  .catch();
});

// Redirect to thank you page for contact form 7
// document.addEventListener( 'wpcf7mailsent', function( event ) {
//   window.location.href = window.location.protocol + '//' + window.location.hostname + '/thank-you/';
// }, false );

