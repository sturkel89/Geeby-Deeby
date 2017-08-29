/**
 * Process the title controls for the preferred item/series forms.
 */
function getPreferredTitle(type) {
    // Extract the basic values:
    var titleID = $('#Preferred_' + type + '_Title_ID').val();
    var titleText = false;
    if (titleID == 'NEW') {
        titleText = $('#Preferred_' + type + '_Title_Text').val();
        if (titleText.length < 1) {
            alert('Title cannot be blank.');
            return false;
        }
    } else {
        titleID = parseInt(titleID);
        if (isNaN(titleID) || titleID < 1) {
            alert('Invalid title selection.');
            return false;
        }
    }

    // Send back the appropriate parameter:
    return titleText
        ? {'title_text': titleText}
        : {'title_id': titleID};
}

var EditionEditor = function() {
    this.type = "Edition";
    this.saveFields = {
        'name': { 'id': '#Edition_Name', emptyError: 'Edition name cannot be blank.' },
        'desc': { 'id': '#Edition_Description' },
        'volume': { 'id': '#Volume' },
        'position': { 'id': '#Position' },
        'replacement_number': { 'id': '#Replacement_Number' },
        'item_id': { 'id': '#Edition_Item_ID' },
        'series_id': { 'id': '#Series_ID' },
        'len': { 'id': '#Edition_Length' },
        'endings': { 'id': '#Edition_Endings' },
        'parent_edition_id': { 'id': '#Parent_Edition_ID' },
        'position_in_parent': { 'id': '#Position_In_Parent' },
        'extent_in_parent': { 'id': '#Extent_In_Parent' }
    };
    this.attributeSelector = '.edition-attribute';
    this.links = {
        'Credit': {
            'saveFields': {
                'note_id': { 'id': '#credit_note', 'nonNumericDefault': '' },
                'person_id': { 'id': '#credit_person', 'nonNumericDefault': '', 'emptyError': 'Please specify a valid person' },
                'pos': { 'id': '#credit_position', 'nonNumericDefault': 0 },
                'role_id': { 'id': '#Role_ID' }
            }
        },
        'Date': {
            'saveFields': {
                'year': { 'id': '#releaseYear', 'nonNumericDefault': 0 },
                'month': {
                    'id': '#releaseMonth', 'nonNumericDefault': 0, 'customValidator': function (month) {
                        if (month < 0 || month > 12) {
                            alert('Please enter a valid month.');
                            return false;
                        }
                        return true;
                    }
                },
                'day': {
                    'id': '#releaseDay', 'nonNumericDefault': 0, 'customValidator': function (day) {
                        if (day < 0 || day > 31) {
                            alert('Please enter a valid day.');
                            return false;
                        }
                        return true;
                    }
                },
                'note_id': { 'id': '#releaseNote', 'nonNumericDefault': '' }
            }
        },
        'FullText': {
            'saveFields': {
                'source_id': { 'id': '#Full_Text_Source_ID' },
                'url': { 'id': '#Full_Text_URL', 'emptyError': 'URL cannot be blank.' }
            }
        },
        'ISBN': {
            'saveFields': {
                'isbn': { 'id': '#isbn', 'emptyError': 'ISBN cannot be blank.' },
                'note_id': { 'id': '#isbn_note', 'nonNumericDefault': '' }
            }
        },
        'Item': {
            'uriField': { 'id': '#item_name', 'nonNumericDefault': '', 'emptyError': 'Please specify a valid item' }
        },
        'OCLCNumber': {
            'saveFields': {
                'oclc_number': { 'id': '#oclc_number', 'emptyError': 'OCLC number cannot be blank.' },
                'note_id': { 'id': '#oclc_number_note', 'nonNumericDefault': '' }
            }
        },
        'Platform': {
            'uriField': { 'id': '#Platform_ID' }
        },
        'PreferredItemTitle': {
            'redrawFunction': function() { $('#Preferred_Item_Title_Text').val(''); },
            'saveFieldsFunction': function() { return getPreferredTitle('Item'); }
        },
        'PreferredPublisher': {
            'saveFields': {
                'pub_id': { 'id': '#Series_Publisher_ID' }
            }
        },
        'PreferredSeriesTitle': {
            'redrawFunction': function() { $('#Preferred_Series_Title_Text').val(''); },
            'saveFieldsFunction': function() { return getPreferredTitle('Series'); }
        },
        'ProductCode': {
            'saveFields': {
                'code': { 'id': '#product_code', 'emptyError': 'Product code cannot be blank.' },
                'note_id': { 'id': '#product_code_note', 'nonNumericDefault': '' }
            }
        }
    }
};
BaseEditor.prototype.registerSubclass(EditionEditor);

/**
 * Redraw the next and previous links:
 */
EditionEditor.prototype.redrawNextAndPrev = function() {
    $('#nextAndPrev').load(this.getLinkUri('NextAndPrev'));
};

/**
 * Override the standard "redraw after save" behavior.
 */
EditionEditor.prototype.redrawAfterSave = function() {
    this.redrawLinks('PreferredItemTitle');
    this.redrawLinks('PreferredPublisher');
    this.redrawLinks('PreferredSeriesTitle');
    this.redrawNextAndPrev();
}

var Edition = new EditionEditor();

/**
 * Override the standard Item redraw list function:
 */
Item.redrawList = function() {
    var edID = $('#Edition_ID').val();
    var url = basePath + '/edit/Edition/' + encodeURIComponent(edID) + '/Item';
    $('#item_list').load(url);
};    

/* Redraw image list:
 */
function redrawImages()
{
    var editionID = $('#Edition_ID').val();
    var url = basePath + '/edit/Edition/' + encodeURIComponent(editionID) + '/Image';
    $('#image_list').load(url);
}

/* Add an image:
 */
function saveImage()
{
    // Extract the basic values:
    var editionID = $('#Edition_ID').val();
    var noteID = parseInt($('#image_note').val());
    if (isNaN(noteID)) {
        noteID = '';
    }
    var pos = parseInt($('#image_position').val());
    if (isNaN(pos)) {
        pos = 0;
    }
    var image = $('#image_path').val();
    var thumb = $('#thumb_path').val();

    // Save the image:
    var url = basePath + '/edit/Edition/' + encodeURIComponent(editionID) + '/Image/NEW';
    var params =
        {image: image, thumb: thumb, note_id: noteID, pos: pos};
    $.post(url, params, function(data) {
        // If save was successful...
        if (data.success) {
            // Update the list.
            redrawImages();
        } else {
            // Save failed -- display error message:
            alert('Error: ' + data.msg);
        }
    }, 'json');
}

/* Remove an image:
 */
function removeImage(image_id, role)
{
    if (!confirm("Are you sure?")) {
        return;
    }

    var editionID = $('#Edition_ID').val();
    var url = basePath + '/edit/Edition/' + encodeURIComponent(editionID) + '/Image/' + encodeURIComponent(image_id);
    $.ajax({url: url, type: "delete", dataType: "json", success: function(data) {
        // If delete was successful...
        if (data.success) {
            // Update the list.
            redrawImages();
        } else {
            // Delete failed -- display error message:
            alert('Error: ' + data.msg);
        }
    }});
}

/* Renumber an image:
 */
function changeImageOrder(image_id)
{
    // Validate parameters:
    if (isNaN(image_id)) {
        alert('Please select a valid image.');
        return;
    }

    // Extract the basic values:
    var editionID = $('#Edition_ID').val();
    var pos = parseInt($('#image_order' + image_id).val());
    if (isNaN(pos)) {
        pos = 0;
    }

    // Renumber the image:
    var url = basePath + '/edit/Edition/' + encodeURIComponent(editionID) + '/ImageOrder/' + encodeURIComponent(image_id);
    $.post(url, {pos: pos}, function(data) {
        // If save was successful...
        if (data.success) {
            // Update the list.
            redrawImages();
        } else {
            // Save failed -- display error message:
            alert('Error: ' + data.msg);
        }
    }, 'json');
}

// Load data and setup autocomplete.
$(document).ready(function() {
  registerAutocomplete('.Item_ID', 'Item');
  registerAutocomplete('.Note_ID', 'Note');
  registerAutocomplete('.Person_ID', 'Person');
  registerAutocomplete('.Series_ID', 'Series');
});
