ClassicEditor
.create( document.querySelector( '#editor' ) ,{
    language: {
        ui: 'en',
        content: 'ar'
      }
})
.then( editor => {
        console.log( editor );
} )
.catch( error => {
        console.error( error );
} );