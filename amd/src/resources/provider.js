import Templates from 'core/templates';
import $ from 'jquery';
import Ajax from 'core/ajax';
import { exception as displayException } from 'core/notification';
import { generatePagination } from '../commun/utils';


let pages = 0;
let filters = [];
let selectedPage = 1;

export const init = () => {
    $('[data-section-name="digitalta-resources-list"]  .dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });
    getAndRenderFilters();
    setActionsFilters();
    getResources();
};

const getAndRenderFilters = async () => {
    const themesRequest = { methodname: 'local_digitalta_themes_get', args: {} };
    const tagsRequest = { methodname: 'local_digitalta_tags_get', args: {} };
    const languagesRequest = { methodname: 'local_digitalta_resources_get_used_langs', args: {} };
    const resourcesTypeRequest = { methodname: 'local_digitalta_resources_types_get', args: {} };

    const themesResponse = await Ajax.call([themesRequest])[0];
    const tagsResponse = await Ajax.call([tagsRequest])[0];
    const languagesResponse = await Ajax.call([languagesRequest])[0];
    const resourcesTypeResponse = await Ajax.call([resourcesTypeRequest])[0];
    // eslint-disable-next-line max-len
    const templateFilterThemes = await Templates.renderForPromise('local_digitalta/_common/filterTheme', { "themes": themesResponse });
    // eslint-disable-next-line max-len
    const templateFilterLanguages = await Templates.renderForPromise('local_digitalta/_common/filterLanguages', { "languages": languagesResponse });
    // eslint-disable-next-line max-len
    const templateFilterTypes = await Templates.renderForPromise('local_digitalta/_common/filterResourceTypes', { "types": resourcesTypeResponse.types });

    Templates.replaceNodeContents("#filterThemeSelect", templateFilterThemes.html, templateFilterThemes.js);
    Templates.replaceNodeContents("#filterLanguageSelect", templateFilterLanguages.html, templateFilterLanguages.js);
    Templates.replaceNodeContents("#filterResourceSelect", templateFilterTypes.html, templateFilterTypes.js);

    const availableTags = tagsResponse.map(function (tag) {
        return tag.name;
    });

    $('#inlineFormInputGroup').on('input', async function () {
        var input = $(this).val().toLowerCase();
        var suggestions = availableTags.filter(function (tag) {
            return tag.toLowerCase().startsWith(input);
        });

        const templateFilterTags = await Templates.renderForPromise('local_digitalta/_common/listTags', { tags: suggestions });
        Templates.replaceNodeContents("#suggestionsTags", templateFilterTags.html, templateFilterTags.js);
    });
};

const setActionsFilters = () => {
    $(document).on("change", "#filterResourceType", function () {
        const selectedId = $("option:selected.enable", this).val();
        const selectedText = $("option:selected.enable", this).text();
        if (selectedText.length > 0) {
            const element = $("option:selected.enable", this);
            element.addClass('disabled');
            element.removeClass('enable');
            const filterObject = { "type": "resource", "value": { id: selectedId, name: selectedText } };
            setFilter(filterObject);
            $("#filterResourceType option:first").prop("selected", true);
            $(element).prop('disabled', true);
        }
    });

    $(document).on("click", "li.page-item.page-link.page", function () {
        const clickedPage = $(this).attr('attr-page');
        if (selectedPage !== clickedPage) {
            selectedPage = parseInt(clickedPage);
            getResources();
        }
    });
    $(document).on("click", "li.page-item.page-link.state", function () {
        const state = $(this).attr('aria-label');
        if (state === 'Next') {
            const posibleNextPage = selectedPage + 1;
            if (posibleNextPage > 0 && posibleNextPage <= pages) {
                selectedPage++;
                getResources();
            }
        } else if (state === 'Previous') {
            const posiblePreviousPage = selectedPage - 1;
            if (posiblePreviousPage > 0 && posiblePreviousPage <= pages) {
                selectedPage--;
                getResources();
            }
        }
    });
    $(document).on("change", ".filterThemeSelect", function () {
        const selectedValue = $("option:selected.enable", this).val();
        const selectedText = $("option:selected.enable", this).text();
        if (selectedText.length > 0) {
            const element = $("option:selected.enable", this);
            element.addClass('disabled');
            element.removeClass('enable');
            const filterObject = { "type": "theme", "value": { id: selectedValue, name: selectedText } };
            setFilter(filterObject);
            $(".filterThemeSelect option:first").prop("selected", true);
            $(element).prop('disabled', true);
        }
    });

    $("#suggestionsTags").on("click", ".tag-item", function () {
        const filterText = { type: "tag", value: $(this).attr('attr-name') };
        setFilter(filterText);
        $("#inlineFormInputGroup").val("");
    });

    $(document).on("keydown", "#authorFilters", async function () {
        const filterText = $(this).val(); // Obtenemos el valor del input
        const request = {
            methodname: 'core_enrol_get_potential_users',
            args: { courseid: 1, enrolid: 1, page: 0, perpage: 10, search: filterText, searchanywhere: true }
        };
        const response = await Ajax.call([request])[0];

        if (response.length === 0) {
            $("#suggestionsAuthors").empty();
        } else {
            const authorsSuggestions = await Templates.renderForPromise('local_digitalta/_common/listAuthors', { users: response });
            Templates.replaceNodeContents("#suggestionsAuthors", authorsSuggestions.html, authorsSuggestions.js);
        }
    });

    $("#suggestionsAuthors").on("click", ".author-item", function () {
        const filterText = { type: "author", value: { id: $(this).attr('attr-id-user'), name: $(this).attr('attr-name') } };
        setFilter(filterText);
        $("#authorFilters").val("");
    });

    $(document).on("change", ".filterLanguageSelect", function () {
        const selectedText = $("option:selected.enable", this).text();
        if (selectedText.length > 0) {
            const element = $("option:selected.enable", this);
            element.addClass('disabled');
            element.removeClass('enable');
            const filterObject = { "type": "language", "value": selectedText };
            setFilter(filterObject);
            $(".filterLanguageSelect option:first").prop("selected", true);
            $(element).prop('disabled', true);
        }
    });

    $("#filters-menu").click(function () {
        if ($(".tagsInputFilter").is(":focus")) {
            $("#suggestionsAuthors").hide();
            $("#suggestionsTags").show();
        } else if ($("#authorFilters").is(":focus")) {
            $("#suggestionsTags").hide();
            $("#suggestionsAuthors").show();
        } else {
            $("#suggestionsTags").hide();
            $("#suggestionsAuthors").hide();
        }
    });

    $(document).on("click", "#removeFilter", function () {
        const filterText = $(this).attr('attr-filter');
        const filterType = $(this).attr('attr-type');
        const filterObject = { "type": filterType, "value": filterText };
        removeFilter(filterObject);
    });
};

const removeFilter = (filterObject) => {
    const index = filters.findIndex((filter) => {
        if (filter.type === 'author' || filter.type === 'resource' || filter.type === 'theme') {
            return filter.type === filterObject.type && filter.value.name === filterObject.value;
        } else {
            return filter.type === filterObject.type && filter.value === filterObject.value;
        }
    });

    if (index > -1) {
        if (filterObject.type === 'theme') {
            let themeSelect = $("#filterThemes");
            let option = $('option[value="' + filters[index].value.id + '"].disabled', themeSelect);
            option.removeClass('disabled');
            option.addClass('enable');
            $("#filterThemes option:first").prop("selected", true);
            $(option).prop('disabled', false);
        } else if (filterObject.type === 'language') {
            let langSelect = $(".filterLanguageSelect");
            let option = $('option[value="' + filterObject.value + '"].disabled', langSelect);
            option.removeClass('disabled');
            option.addClass('enable');
            $(".filterLanguageSelect option:first").prop("selected", true);
            $(option).prop('disabled', false);
        } else if (filterObject.type === 'resource') {
            let resourceSelect = $("#filterResourceType");
            let option = $('option[value="' + filters[index].value.id + '"].disabled', resourceSelect);
            option.removeClass('disabled');
            option.addClass('enable');
            $("#filterResourceType option:first").prop("selected", true);
            $(option).prop('disabled', false);
        }
        filters.splice(index, 1);
        selectedPage = 1;
        renderFilters();
    }
};

const setFilter = (filterObject) => {
    filters.push(filterObject);
    selectedPage = 1;
    renderFilters();
};

const renderFilters = () => {
    getResources();
    const filtersMap = filters.map((filter) => {
        if (filter.type === 'author' || filter.type === 'resource' || filter.type === 'theme') {
            return { type: filter.type, value: filter.value.name };
        } else {
            return { type: filter.type, value: filter.value };
        }
    });
    Templates.renderForPromise('local_digitalta/_common/filterList', { filters: filtersMap }).then(({ html, js }) => {
        Templates.replaceNodeContents("#filtersList", html, js);
    }).catch((error) => displayException(error));
};

const getResources = async () => {
    const mappedFilters = filters.map((filter) => {
        if (filter.type === 'author' || filter.type === 'resource' || filter.type === 'theme') {
            return { type: filter.type, value: filter.value.id };
        }
        return filter;
    });

    const resourcesRequest = {
        methodname: 'local_digitalta_resources_get_by_pagination',
        args: { filters: JSON.stringify(mappedFilters), pagenumber: selectedPage }
    };

    const resourcesResponse = await Ajax.call([resourcesRequest])[0];
    if (resourcesResponse.data.length === 0) {
        Templates.renderForPromise('local_digitalta/_common/empty-view', resourcesResponse).then(({ html, js }) => {
            Templates.replaceNodeContents("#list-resources-body", html, js);
        }).catch((error) => displayException(error));
        $(".digitalta.pagination").hide();
    } else {
        let obj = {
            "resources": resourcesResponse,
            "viewtagurl": M.cfg.wwwroot + "/local/digitalta/pages/tags/view.php?type=tag&id=",
            "viewthemeurl": M.cfg.wwwroot + "/local/digitalta/pages/tags/view.php?type=theme&id="
        };
        let paginationArray = generatePagination(resourcesResponse.pages, selectedPage);
        // eslint-disable-next-line max-len
        const resourcesList = await Templates.renderForPromise('local_digitalta/resources/dashboard/resource-list', obj);
        pages = resourcesResponse.pages;
        const paginationList = await Templates.renderForPromise("local_digitalta/_common/pagination", {
            pages: paginationArray,
            pagesCount: paginationArray.length,
            currentPage: selectedPage,
            disablePrevious: selectedPage <= 1,
            disableNext: selectedPage >= pages,
        });
        Templates.replaceNodeContents("#list-resources-body", resourcesList.html, resourcesList.js);
        Templates.replaceNodeContents("#digital-pagination", paginationList.html, paginationList.js);
        $(".digitalta.pagination").show();
    }
};