let changed = 0;

ClassicEditor
    .create( document.querySelector( '#content' ), {
        mediaEmbed: {
            previewsInData: true
        },
        wordCount: {
            onUpdate: stats => {
                if (document.getElementById('total-words')) {
                    document.getElementById('total-words').innerText = stats.words;
                    document.getElementById('total-characters').innerText = stats.characters;
                }
            }
        },
        language: 'nl',
        ckfinder: {
            options: {
                language: 'nl'
            }
        },
        link: {
            decorators: {
                openInNewTab: {
                    mode: 'manual',
                    label: 'Open in een nieuw tab',
                    attributes: {
                        target: '_blank',
                        rel: 'noopener noreferrer'
                    }
                }
            }
        },
        toolbar: [
            'heading',
            '|',
            'bold',
            'italic',
            'strikethrough',
            'underline',
            'link',
            '|',
            'bulletedList',
            'numberedList',
            '|',
            'insertTable',
            'alignment',
            'fontColor',
            'horizontalLine',
            'imageInsert',
            'removeFormat',
            'specialCharacters',
            'mediaEmbed'
        ],
        image: {
            toolbar: [
                'imageTextAlternative',
                'imageStyle:full',
                'imageStyle:side',
                'linkImage'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        },
    } )
    .then( editor => {
        window.content_editor = editor;
        const original_content = JSON.stringify(editor.getData());

        editor.model.document.on('change', () => {
            const new_val = JSON.stringify(editor.getData());
            changed = original_content.localeCompare(new_val);
        });
    } )
    .catch( err => {
        console.error( err.stack );
    } );

window.onbeforeunload = function(){
    if (changed !== 0) {
        return 'Are you sure you want to leave?'
    }
}

ClassicEditor
    .create( document.querySelector( '#ingredients' ), {
        language: 'nl',
        link: {
            decorators: {
                openInNewTab: {
                    mode: 'manual',
                    label: 'Open in een nieuw tab',
                    attributes: {
                        target: '_blank',
                        rel: 'noopener noreferrer'
                    }
                }
            }
        },
        toolbar: ['heading', '|',  'bold', 'italic', 'link', 'bulletedList', 'numberedList'],
    } )
    .then( editor => {
        window.ingredients_editor = editor;
        const original_ingredients = JSON.stringify(editor.getData());

        editor.model.document.on('change', () => {
            const new_val = JSON.stringify(editor.getData());
            changed = original_ingredients.localeCompare(new_val);
        });
    } )
    .catch( err => {
        console.error( err.stack );
    } );

ClassicEditor
    .create( document.querySelector( '#kitchen_equipment' ), {
        language: 'nl',
        link: {
            decorators: {
                openInNewTab: {
                    mode: 'manual',
                    label: 'Open in een nieuw tab',
                    attributes: {
                        target: '_blank',
                        rel: 'noopener noreferrer'
                    }
                }
            }
        },
        toolbar: ['heading', '|',  'bold', 'italic', 'link', 'bulletedList', 'numberedList' ],
    } )
    .then( editor => {
        window.kitchen_equipment_editor = editor;
        const original_kitchen_equipment = JSON.stringify(editor.getData());

        editor.model.document.on('change', () => {
            const new_val = JSON.stringify(editor.getData());
            changed = original_kitchen_equipment.localeCompare(new_val);
        });
    } )
    .catch( err => {
        console.error( err.stack );
    } );

$(document).ready(function() {
    // const content_form = $('#content-form');
    // if (content_form.length > 0) {
    //     window.setInterval(function(){
    //         console.log(content_form.attr('action'));
    //         if (changed !== 0) {
    //             let the_form = document.getElementById('content-form');
    //             let fd = new FormData(the_form);
    //
    //             $.ajax({
    //                 type: 'POST',
    //                 url: content_form.attr('action') + '?autosave=true',
    //                 data: fd,
    //                 processData: false,
    //                 contentType: false,
    //                 enctype: 'multipart/form-data',
    //                 cache: false,
    //                 success: function (data) {
    //                 }
    //             });
    //         }
    //     }, 5000);
    // }

    $('.multiselect').multiselect({
        maxHeight: 200,
        enableCaseInsensitiveFiltering: true,
        filterPlaceholder: 'Filteren',
        selectAllText: 'Alles selecteren',
        nonSelectedText: 'Niks geselecteerd',
        allSelectedText: 'Alles geselecteerd',
        nSelectedText: 'geselecteerd',
        resetText: 'Reset',
        buttonContainer: '<div class="btn-group w-100" />'
    });

    let rt = $('input[name="recipe_tags"]');
    let xhr_url = $(rt).data('url');

    let suggestions = [];
    if (tag_suggestions.length) {
        suggestions = $.parseJSON(tag_suggestions);
    }

    $(rt).amsifySuggestags({
        suggestions: suggestions,
        selectOnHover: false,
        suggestionsAction : {
            timeout: -1,
            minChars: 2,
            minChange: -1,
            delay: 700,
            type: 'GET',
            url: xhr_url
        }
    });
});