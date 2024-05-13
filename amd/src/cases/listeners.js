import $ from "jquery";
import {
    addTextSection,
    changeSectionToEdit,
    deleteSection,
    removeSection,
    upsertSection,
    showDeleteSectionModal,
    showSaveCase,
    changeStatusToComplete
} from "./form";
import {
    createTinyMCE,
    removeTinyMCEFromArea
} from 'local_dta/tiny/manage';

/**
 * Set event listeners for the page.
 * @return {void}
 */
export default function setEventListeners() {
    // Add a new text section
    $(document).on('click', '#add-section', () => {
        addTextSection();
    });
    // Remove a section
    $(document).on('click', '.section-close-button', function () {
        const id = $(this).data('id');
        removeSection(id);
        removeTinyMCEFromArea(`content_${id}`);
    });
    // Edit the section
    $(document).on('click', '.section-edit-button', function () {
        const id = $(this).data('id');
        upsertSection(id);
        removeTinyMCEFromArea(`content_${id}`);
    });
    // Change the section to edit mode
    $(document).on('click', '.section-to-edit-button', async function () {
        const id = $(this).data('id');
        await changeSectionToEdit(true, id);
        createTinyMCE(`content_${id}`);
    });
    // Show section delete modal
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
