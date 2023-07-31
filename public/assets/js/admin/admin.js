let changed = 0;

if ($('#content')) {
    ClassicEditor
        .create(document.querySelector('#content'), {
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
            language: 'en',
            ckfinder: {
                options: {
                    language: 'en'
                }
            },
            link: {
                decorators: {
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    },
                    openInNewTabNoFollow: {
                        mode: 'manual',
                        label: 'Open in a new tab + nofollow',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer nofollow'
                        }
                    },
                    noFollow: {
                        mode: 'manual',
                        label: 'NoFollow',
                        attributes: {
                            rel: 'nofollow noopener noreferrer'
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
        })
        .then(editor => {
            window.content_editor = editor;
            const original_content = JSON.stringify(editor.getData());

            editor.model.document.on('change', () => {
                const new_val = JSON.stringify(editor.getData());
                changed = original_content.localeCompare(new_val);
            });
        })
        .catch(err => {
            console.error(err.stack);
        });
}

window.onbeforeunload = function(){
    if (changed !== 0) {
        return 'Are you sure you want to leave?'
    }
}

if ($('#ingredients')) {
    ClassicEditor
        .create(document.querySelector('#ingredients'), {
            language: 'en',
            link: {
                decorators: {
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    },
                    openInNewTabNoFollow: {
                        mode: 'manual',
                        label: 'Open in a new tab + nofollow',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer nofollow'
                        }
                    },
                    noFollow: {
                        mode: 'manual',
                        label: 'NoFollow',
                        attributes: {
                            rel: 'nofollow noopener noreferrer'
                        }
                    }
                }
            },
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList'],
        })
        .then(editor => {
            window.ingredients_editor = editor;
            const original_ingredients = JSON.stringify(editor.getData());

            editor.model.document.on('change', () => {
                const new_val = JSON.stringify(editor.getData());
                changed = original_ingredients.localeCompare(new_val);
            });
        })
        .catch(err => {
            console.error(err.stack);
        });
}

if ($('#kitchen_equipment')) {
    ClassicEditor
        .create(document.querySelector('#kitchen_equipment'), {
            language: 'en',
            link: {
                decorators: {
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    },
                    openInNewTabNoFollow: {
                        mode: 'manual',
                        label: 'Open in a new tab + nofollow',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer nofollow'
                        }
                    },
                    noFollow: {
                        mode: 'manual',
                        label: 'NoFollow',
                        attributes: {
                            rel: 'nofollow noopener noreferrer'
                        }
                    }
                }
            },
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList'],
        })
        .then(editor => {
            window.kitchen_equipment_editor = editor;
            const original_kitchen_equipment = JSON.stringify(editor.getData());

            editor.model.document.on('change', () => {
                const new_val = JSON.stringify(editor.getData());
                changed = original_kitchen_equipment.localeCompare(new_val);
            });
        })
        .catch(err => {
            console.error(err.stack);
        });
}

if ($('#seo_text')) {
    ClassicEditor
        .create(document.querySelector('#seo_text'), {
            mediaEmbed: {
                previewsInData: true
            },
            language: 'en',
            ckfinder: {
                options: {
                    language: 'en'
                }
            },
            link: {
                decorators: {
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    },
                    openInNewTabNoFollow: {
                        mode: 'manual',
                        label: 'Open in a new tab + nofollow',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer nofollow'
                        }
                    },
                    noFollow: {
                        mode: 'manual',
                        label: 'NoFollow',
                        attributes: {
                            rel: 'nofollow noopener noreferrer'
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
        })
        .then(editor => {
            window.content_editor = editor;
            const original_content = JSON.stringify(editor.getData());

            editor.model.document.on('change', () => {
                const new_val = JSON.stringify(editor.getData());
                changed = original_content.localeCompare(new_val);
            });
        })
        .catch(err => {
            console.error(err.stack);
        });
}

if ($('#faq')) {
    ClassicEditor
        .create(document.querySelector('#faq'), {
            language: 'en',
            link: {
                decorators: {
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    },
                    openInNewTabNoFollow: {
                        mode: 'manual',
                        label: 'Open in a new tab + nofollow',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer nofollow'
                        }
                    },
                    noFollow: {
                        mode: 'manual',
                        label: 'NoFollow',
                        attributes: {
                            rel: 'nofollow noopener noreferrer'
                        }
                    }
                }
            },
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList'],
        })
        .then(editor => {
            window.faq_editor = editor;
            const original_faq = JSON.stringify(editor.getData());

            editor.model.document.on('change', () => {
                const new_val = JSON.stringify(editor.getData());
                changed = original_faq.localeCompare(new_val);
            });
        })
        .catch(err => {
            // console.error(err.stack);
        });
}

if ($('#seo_content')) {
    ClassicEditor
        .create(document.querySelector('#seo_content'), {
            language: 'en',
            link: {
                decorators: {
                    openInNewTab: {
                        mode: 'manual',
                        label: 'Open in a new tab',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer'
                        }
                    },
                    openInNewTabNoFollow: {
                        mode: 'manual',
                        label: 'Open in a new tab + nofollow',
                        attributes: {
                            target: '_blank',
                            rel: 'noopener noreferrer nofollow'
                        }
                    },
                    noFollow: {
                        mode: 'manual',
                        label: 'NoFollow',
                        attributes: {
                            rel: 'nofollow noopener noreferrer'
                        }
                    }
                }
            },
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList'],
        })
        .then(editor => {
            window.seo_content_editor = editor;
            const original_seo_content = JSON.stringify(editor.getData());

            editor.model.document.on('change', () => {
                const new_val = JSON.stringify(editor.getData());
                changed = original_seo_content.localeCompare(new_val);
            });
        })
        .catch(err => {
            // console.error(err.stack);
        });
}

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
        filterPlaceholder: 'Filter',
        selectAllText: 'Select all',
        nonSelectedText: 'Nothing selected',
        allSelectedText: 'Everything selected',
        nSelectedText: 'select',
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
