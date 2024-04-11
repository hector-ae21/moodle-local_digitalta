import $ from "jquery";

/**
 * Set event listeners for the page.
 * @return {void}
 */
export function setEventListeners() {
  // Add a new text section
  $(document).on('click', '#add-section', () => {
    addTextSection();
});
// Edit the header
$(document).on('click', '#header-edit-button', () => {
    upsertHeaderSection();
});
// Change the header section to edit mode
$(document).on('click', '#header-to-edit-button', () => {
    changeSectionHeaderToEdit(true);
    createTinyMCE('section-header-description');
});
// Change the header section to view mode withour editing
$(document).on('click', '#header-edit-close-button', () => {
    changeSectionHeaderToEdit();
    removeTinyMCEFromArea('section-header-description');
});
// Remove a section
$(document).on('click', '.section-close-button', function() {
    const id = $(this).data('id');
    removeSection(id);
    removeTinyMCEFromArea(`content_${id}`);
});
// Edit the section
$(document).on('click', '.section-edit-button', function() {
    const id = $(this).data('id');
    upsertSection(id);
    removeTinyMCEFromArea(`content_${id}`);
});
// Change the section to edit mode
$(document).on('click', '.section-to-edit-button', function() {
    changeSectionToEdit(true, $(this).data('id'));
    const id = $(this).data('id');
    createTinyMCE(`content_${id}`);
});
// Showt section delete modal
$(document).on('click', '.section-delete-button', function() {
    showDeleteSectionModal($(this).data('id'));
});
// Delete section
$(document).on('click', '#confirmDelete', function() {
    deleteSection();
});
// Save button
$(document).on('click', '#save-case-button', function() {
    showSaveCase();
});
// Change status to complete
$(document).on('click', '#complete-case-button', function() {
    changeStatusToComplete();
});
}