$(function() {

    /*set select to right to left*/
    $('.select2').select2({
        dir: "rtl",
    })

/*init avatar*/
$('#avatar').spartanMultiImagePicker({
    fieldName: 'avatar[]',
    maxCount: 1,
    groupClassName: 'col-md-12',
    dropFileLabel: "اینجا بیندازید",
    altField: '.observer-example-alt'
});
/*init input mask*/
    $('[data-mask]').mask();
    $('button[type=submit]').click(function(){
        $('[data-mask]').unmask()
    })
    /*init tinymce*/
    tinymce.init({
        selector: 'textarea.tinymce',
        plugins: 'advlist autolink link lists preview table code pagebreak image',
        menubar: false,
        language: 'fa',
        height: 300,
        relative_urls: false,
        toolbar: 'undo redo | removeformat preview code | fontsizeselect bullist numlist | alignleft aligncenter alignright alignjustify | bold italic | pagebreak table link | image',
    });
})
